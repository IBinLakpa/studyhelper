// script/accordion.js

//without DOMContentLoaded, accordions will not load
document.addEventListener("DOMContentLoaded", (event) => {
    document.querySelectorAll('.section').forEach((el) => {
        new Accordion(el);
    });
});

class Accordion {
    constructor(el) {
        // Store the <details> element
        this.el = el;
        // Store the <summary> element
        this.summary = el.querySelector('summary');
        // Store the <div class="content"> element
        this.content = el.querySelector('.content');

        // Store the animation object (so we can cancel it if needed)
        this.animation = null;
        // Store if the element is closing
        this.isClosing = false;
        // Store if the element is expanding
        this.isExpanding = false;
        // Detect user clicks on the summary element
        this.summary.addEventListener('click', (e) => this.onClick(e));
    }

    onClick(e) {
        // Stop default behaviour from the browser
        e.preventDefault();
        // Add an overflow on the <details> to avoid content overflowing
        this.el.style.overflow = 'hidden';
        // Check if the element is being closed or is already closed
        if (this.isClosing || !this.el.open) {
            this.open();
        // Check if the element is being opened or is already open
        } else if (this.isExpanding || this.el.open) {
            this.shrink();
        }
    }

    shrink() {
        // Set the element as "being closed"
        this.isClosing = true;

        // Rotate the marker
        this.summary.classList.remove('rotated');

        // Store the current height of the element
        const startHeight = `${this.el.offsetHeight}px`;
        // Calculate the height of the summary
        const endHeight = `${this.summary.offsetHeight}px`;

        // If there is already an animation running
        if (this.animation) {
            // Cancel the current animation
            this.animation.cancel();
        }

        // Start a WAAPI animation
        this.animation = this.el.animate({
            // Set the keyframes from the startHeight to endHeight
            height: [startHeight, endHeight]
        }, {
            duration: 400,
            easing: 'ease-out'
        });

        // When the animation is complete, call onAnimationFinish()
        this.animation.onfinish = () => this.onAnimationFinish(false);
        // If the animation is cancelled, isClosing variable is set to false
        this.animation.oncancel = () => this.isClosing = false;
    }

    open() {
        // Rotate the marker
        this.summary.classList.add('rotated');

        // Apply a fixed height on the element
        this.el.style.height = `${this.el.offsetHeight}px`;
        // Force the [open] attribute on the details element
        this.el.open = true;
        // Wait for the next frame to call the expand function
        window.requestAnimationFrame(() => this.expand());
    }

    expand() {
        // Set the element as "being expanding"
        this.isExpanding = true;
        // Get the current fixed height of the element
        const startHeight = `${this.el.offsetHeight}px`;
        // Calculate the open height of the element (summary height + content height)
        const endHeight = `${this.summary.offsetHeight + this.content.offsetHeight}px`;

        // If there is already an animation running
        if (this.animation) {
            // Cancel the current animation
            this.animation.cancel();
        }

        // Start a WAAPI animation
        this.animation = this.el.animate({
            // Set the keyframes from the startHeight to endHeight
            height: [startHeight, endHeight]
        }, {
            duration: 200,
            easing: 'ease-in'
        });
        // When the animation is complete, call onAnimationFinish()
        this.animation.onfinish = () => this.onAnimationFinish(true);
        // If the animation is cancelled, isExpanding variable is set to false
        this.animation.oncancel = () => this.isExpanding = false;
    }

    onAnimationFinish(open) {
        // Set the open attribute based on the parameter
        this.el.open = open;
        // Clear the stored animation
        this.animation = null;
        // Reset isClosing & isExpanding
        this.isClosing = false;
        this.isExpanding = false;
        // Remove the overflow hidden and the fixed height
        this.el.style.height = this.el.style.overflow = '';

        // If the element is not open, remove the rotating class to reset the marker
        if (!open) {
            this.summary.classList.remove('rotated');
        }
    }
}
//without DOMContentLoaded, accordions will not load
document.addEventListener("DOMContentLoaded", (event) => {
    document.querySelectorAll('.content').forEach((el) => {
        el.innerHTML=parseBBCode(el.innerHTML);
    });
});
// BBCode parser
function parseBBCode(bbcode) {
    const replacements = [
        { regex: /\[b\](.*?)\[\/b\]/g, replace: '<strong>$1</strong>' },
        { regex: /\[i\](.*?)\[\/i\]/g, replace: '<em>$1</em>' },
        { regex: /\[u\](.*?)\[\/u\]/g, replace: '<span style="text-decoration: underline;">$1</span>' },
        { regex: /\[s\](.*?)\[\/s\]/g, replace: '<span style="text-decoration: line-through;">$1</span>' },
        { regex: /\[sup\](.*?)\[\/sup\]/g, replace: '<sup>$1</sup>' },
        { regex: /\[sub\](.*?)\[\/sub\]/g, replace: '<sub>$1</sub>' },
        { regex: /\[ol\](.*?)\[\/ol\]/gs, replace: '<ol>$1</ol>' },
        { regex: /\[ul\](.*?)\[\/ul\]/gs, replace: '<ul>$1</ul>' },
        { regex: /\[li\](.*?)\[\/li\]/g, replace: '<li>$1</li>' },
        { regex: /\[url\](.*?)\[\/url\]/g, replace: '<a href="$1">$1</a>' },
        { regex: /\[url=(.*?)\](.*?)\[\/url\]/g, replace: '<a href="$1">$2</a>' },
        { regex: /\[img=(.*?)\](.*?)\[\/img\]/g, replace: '<figure><img src="$2" alt="$1"><figcaption>$1</figcaption></figure>' },
        { regex: /\[vid=(.*?)\](.*?)\[\/vid\]/g, replace: '<video controls src="$2">$1</video>' },
        { regex: /\[blockquote\](.*?)\[\/blockquote\]/gs, replace: '<blockquote>$1</blockquote>' },
        { regex: /\[code\](.*?)\[\/code\]/gs, replace: '<pre><code>$1</code></pre>' },
        { regex: /\[spoiler=(.*?)\](.*?)\[\/spoiler\]/gs, replace: '<details><summary>$1</summary>$2</details>' },
        { regex: /\[table=(.*?)\](.*?)\[\/table\]/gs, replace: '<div style="display:grid;grid-template-columns:repeat($1, auto); gap:1em;">$2</div>' },
        { regex: /\[td\](.*?)\[\/td\]/g, replace: '<span>$1</span>' }
    ];

    return replacements.reduce((text, { regex, replace }) => text.replace(regex, replace), bbcode);
}