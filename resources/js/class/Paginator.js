export class Paginator {
    constructor(params) {
        if(!params) params = {};
        this.selector = params.selector ?? '#articles';

        this._init();
    }

    _init() {
        if(!document.querySelector('.pager a')) return;

        const block = document.querySelector(this.selector);
        if(!block) return;

        this.createHandler(block);

    }

    createHandler(block) {
        const obj = this;
        block.addEventListener('click', function (e) {
            const target = e.target;
            if(target.hasAttribute('href') && target.closest('.pager')) {
                e.preventDefault();
                let page = target.getAttribute('href').split('page=')[1];
                document.querySelector('#hidden_page').value = page;
                obj.fetch_data(page, block);
            }
        });
    }

    fetch_data(page = 1, block) {
        axios.get(`?page=${page}`)
            .then(res => {
                block.innerHTML = res.data;
            })
    }
}
