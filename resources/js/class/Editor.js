import ClassicEditor from '@ckeditor/ckeditor5-build-classic/build/ckeditor';

export class Editor {
    constructor(selector) {
        this.selector = selector;
        this.editors = {
            'receipts': {},
            'articles': {}
        };

        this._init()
    }

    _init() {
        if(!document.querySelectorAll(this.selector).length) {
            return;
        }
        const obj = this;

        [...document.querySelectorAll(this.selector)].forEach((element) => {
            obj.createEditor(element);
        })
    }

    createEditor (element) {
        const type = element.dataset.type;
        const id = `#${element.getAttribute('id')}`;

        if (!this.editors.hasOwnProperty(type) || this.editors[type].hasOwnProperty(id)) {
            return true;
        }

        const editor = (this.editors.hasOwnProperty(type))
            ? ClassicEditor.create(element)
            : '';

        editor.then(editor => this.editors[type][id] = editor).catch(err => console.error(err.stack));
    }
}
