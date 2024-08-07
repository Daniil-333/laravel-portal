import {Editor} from "../class/Editor.js";
import customSelect from 'custom-select';
import {Paginator} from "../class/Paginator.js";
import {PaginatorReceipt} from "../class/PaginatorReceipt";

export class Base {
    constructor() {

        let protoMain = Object.getPrototypeOf(this);
        let protoBase = Object.getPrototypeOf(protoMain);
        this.callInitByProto(protoBase);
        this.callInitByProto(protoMain);
    }

    callInitByProto(proto) {
        let vars = Object.getOwnPropertyNames(proto);

        for(let method of vars) {
            if(method.match(/^init[\w]+/,method)) {
                this[method]();
            }
        }
    }

    initConfirmRemoveUser() {
        if(!document.querySelector('.js-users')) return;

        document.querySelector('.js-users')
            .addEventListener('click', function (e) {
                if(e.target.classList.contains('js-confirm-del-user')) {
                    const target = e.target;

                    if(confirm('Точно удаляем этого Юзера?')) {
                        axios.post(`/users/${target.dataset.id}/delete`).then(res => {
                            console.log(res)
                        })
                    }
                }
            })
    }

    initEditor() {
        if(!document.querySelector('.js-editor-classic')) {
            return;
        }

        new Editor('.js-editor-classic');
    }

    initPreviewImage() {
        if(!document.querySelector('form[name="articleForm"]')) {
            return;
        }

        document.querySelector('input[type="file"]')
            .addEventListener('change', function (e) {
                const preview = this.parentNode;
                const [file] = this.files;

                if (file) {
                    let img = new Image();
                    img.src = URL.createObjectURL(file);
                    img.classList.add('image-preview');
                    if(preview.previousElementSibling) {
                        preview.previousElementSibling.remove();
                    }
                    preview.insertAdjacentElement('beforebegin', img);
                }
            })
    }

    initPreviewVideo() {
        if(!document.querySelector('form[name="receiptForm"]')) {
            return;
        }

        document.querySelector('input[type="file"]')
            .addEventListener('change', function (e) {
                let previewVideo = this.parentNode.previousElementSibling;
                let previewImage = this.parentNode.previousElementSibling.previousElementSibling;
                const [file] = this.files;

                if(file?.type.includes('image')) {
                    previewVideo.hidden = true;
                    previewVideo.src = '';
                    previewImage.hidden = false;
                    previewImage.classList.add('image-preview');
                    previewImage.src = URL.createObjectURL(file);
                }
                else {
                    if (file) {
                        previewVideo.hidden = false;
                        previewImage.hidden = true;
                        previewImage.src = '';
                        previewVideo.type = file.type;
                        previewVideo.src = URL.createObjectURL(file);
                    }
                }
            })
    }

    initAjaxPaginateReceipts() {
        if(location.pathname.match('receipts')) {
            new PaginatorReceipt({
                selector: '#receipts'
            });
        }
    }

    initAjaxPaginateCommon() {
        if(!location.pathname.match('receipts')) {
            new Paginator();
        }
    }

    initCustomSelect() {
        if(document.querySelector('#js-tag-category')) {
            const selectCustom = customSelect('#js-tag-category')[0];
            selectCustom.select.addEventListener('change', (e) => {
                if(document.querySelector('.filter')) {
                    document.querySelector('.filter')
                        .dispatchEvent(new Event('change'));
                }
            });
        }

        if(document.querySelector('#js-role-users')) {
            const selectCustom = customSelect('#js-role-users')[0];
        }
    }

    initSorting() {
        if(!document.querySelector('[data-sorting]')) {
            return;
        }

        document.querySelector('[data-sorting]').addEventListener('click', function (e) {
            const target = e.target;

            if(!target.dataset.sort || !target.dataset.type) return;

            if(!target.classList.contains('_active')) {
                [...this.querySelectorAll('.sortFilter__btn')].forEach(btn => {
                    btn.classList.remove('_active');
                });
                target.classList.add('_active');
            }

            target.dataset.sort = target.dataset.sort =='desc' ? 'asc' : 'desc';

            const filter = window.ReceiptPaginator.getDataFromFilter();
            // console.log(filter);

            if(!parseInt(filter.category) && !filter.search && !filter.tags.length) {
                axios.post('/ajax_sorting_receipts', {
                    sort: target.dataset.sort,
                    type: target.dataset.type,
                }).then(res => {
                    if(res.data.status == 'error') {
                        let error = this.querySelector('.sortFilter__error');
                        error.innerHTML = res.data.msg;
                        setTimeout(() => {error.innerHTML = ''}, 2000);
                    }
                    else {
                        document.querySelector('#receipts').innerHTML = res.data;
                    }
                })
            }
            else {
                document.querySelector('.filter').dispatchEvent(new Event('change'));
            }
        })
    }
}
