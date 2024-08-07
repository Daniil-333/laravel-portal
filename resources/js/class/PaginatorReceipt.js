export class PaginatorReceipt {
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
        this.handleRequestFilter(block);

        window.ReceiptPaginator = this;
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
        let {tags, category, search, sort} = this.getDataFromFilter();
        axios.get(`?page=${page}&category=${category}&search=${search}&tags=${tags}&sort=${sort.view}&type=${sort.type}`)
            .then(res => {
                block.innerHTML = res.data;
            })
    }

    getDataFromFilter() {
        let tags = [...document.querySelectorAll('.js-tag-filter:checked')]
            .map(chbox => parseInt(chbox.value));
        let category = document.querySelector('.js-tag-category').value;
        let search = document.querySelector('.js-search').value.trim();
        let sort = {
            view: document.querySelector('[data-sort][data-type]._active').dataset.sort,
            type: document.querySelector('[data-sort][data-type]._active').dataset.type,
        }

        return {tags, category, search, sort};
    }

    handleRequestFilter(block) {
        if(!document.querySelector('.js-tag-filter')) return;

        const empty = document.querySelector('.js-empty-filter');

        document.querySelector('.filter')
            .addEventListener('change', this.requestFilter.bind(this, block, empty));
    }

    requestFilter(receipts, empty) {
        axios.post('/ajax_filter_receipts', {
            filter: this.getDataFromFilter()
        }).then(res => {
            if(res.data.status == 'error') {
                receipts.innerHTML = res.data.msg;
            }
            else {
                receipts.innerHTML = res.data;
                res.data ?
                    empty.classList.add('hidden') :
                    empty.classList.remove('hidden');
            }
        })
    }
}
