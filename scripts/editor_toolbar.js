// script/editor_toolbar.js

//without DOMContentLoaded, toolbars will not load
document.addEventListener("DOMContentLoaded", (event) => {
    document.querySelectorAll('.editor').forEach((el) => {
      new EditorToolbar(el);
    });
});

class EditorToolbar {
    constructor(el) {
        this.el = el;
        this.textarea = el.querySelector('textarea');
        this.label = el.querySelector('label');
        this.createToolbar();
    }

    createToolbar() {
        const toolbarHTML = `
            <div class="toolbar">
                <ul class="tool-list">
                    <li class="tool">
                        <button type="button" class="tool--btn" data-tag="b"><i class='fas fa-bold'></i></button>
                        <button type="button" class="tool--btn" data-tag="i"><i class='fas fa-italic'></i></button>
                        <button type="button" class="tool--btn" data-tag="u"><i class='fas fa-underline'></i></button>
                        <button type="button" class="tool--btn" data-tag="s"><i class="fa-solid fa-strikethrough"></i></button>
                    </li>
                    <li class="tool">
                        <button type="button" class="tool--btn" data-tag="sup"><i class="fa-solid fa-superscript"></i></button>
                        <button type="button" class="tool--btn" data-tag="sub"><i class="fa-solid fa-subscript"></i></button>
                    </li>
                    <li class="tool">
                        <button type="button" class="tool--btn" data-function="wrapImg"><i class="fa-solid fa-image"></i></button>
                        <button type="button" class="tool--btn" data-function="wrapVid"><i class="fa-solid fa-video"></i></button>
                    </li>
                    <li class="tool">
                        <button type="button" class="tool--btn" data-function="wrapOList"><i class='fas fa-list-ol'></i></button>
                        <button type="button" class="tool--btn" data-function="wrapUList"><i class='fas fa-list-ul'></i></button>
                    </li>
                    <li class="tool">
                        <button type="button" class="tool--btn" data-function="wrapIMath"><i class="fa-solid fa-square-root-variable"></i></button>
                        <button type="button" class="tool--btn" data-function="wrapMath"><i class="fa-solid fa-wave-square"></i></button>
                    </li>
                    <li class="tool">
                        <button type="button" class="tool--btn" data-tag="url"><i class='fas fa-link'></i></button>
                    </li>
                    <li class="tool">
                        <button type="button" class="tool--btn" data-tag="blockquote"><i class="fa-solid fa-quote-left"></i></button>
                        <button type="button" class="tool--btn" data-tag="code"><i class="fa-solid fa-code"></i></button>
                        <button type="button" class="tool--btn" data-function="wrapTable"><i class="fa-solid fa-table"></i></button>
                        <button type="button" class="tool--btn" data-tag="spoiler"><i class="fa-solid fa-eye-slash"></i></button>
                    </li>
                </ul>
            </div>`;
        this.label.insertAdjacentHTML('afterend', toolbarHTML);
        this.addEventListeners();
    }

    addEventListeners() {
        const buttons = this.el.querySelectorAll('.tool--btn');
        buttons.forEach(button => {
            button.addEventListener('click', (event) => {
                const tag = button.getAttribute('data-tag');
                const func = button.getAttribute('data-function');
                if (tag) {
                    this.wrapText(tag);
                } else if (func) {
                    this[func]();
                }
            });
        });
    }

    wrap(startTag, endTag = "") {
        const textarea = this.textarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value.substring(start, end);
        const before = textarea.value.substring(0, start);
        const after = textarea.value.substring(end, textarea.value.length);
        textarea.value = before + startTag + text + endTag + after;
    }

    wrapText(tag) {
        this.wrap(`[${tag}]`, `[/${tag}]`);
    }

    wrapIMath() {
        this.wrap("\\(", "\\)");
    }

    wrapMath() {
        this.wrap("$$", "$$");
    }

    wrapTable() {
        let cols = prompt("Please enter number of columns in table:", "4");
        cols = parseInt(cols, 10);
        if (Number.isInteger(cols) && cols > 0) {
            let extra = "";
            for (let i = 0; i < cols; i++) {
                extra += "[td][/td]";
            }
            this.wrap(`[table=${cols}]${extra}`, "[/table]");
        } else {
            alert("Invalid number of columns.");
        }
    }

    wrapMedia(tag) {
        let src = prompt(`Please enter ${tag} url:`);
        let caption = prompt("Please enter caption:");
        this.wrap(`[${tag}=${caption}]${src}[/${tag}]`);
    }

    wrapImg() {
        this.wrapMedia("img");
    }

    wrapVid() {
        this.wrapMedia("vid");
    }
    wrapOList(){
        console.log('ol');
        this.wrapList('ol');
    }
    wrapUList(){
        this.wrapList('ul');
    }
    wrapList(tag) {
        let l = prompt("Please enter number of items in list:", "6");
        l = parseInt(l, 10);
        if (Number.isInteger(l) && l > 0) {
            let extra = "";
            for (let i = 0; i < l; i++) {
                extra += "[li][/li]";
            }
            this.wrap(`[${tag}]${extra}`, `[/${tag}]`);
        } else {
            alert("Invalid number of items.");
        }
    }
}
