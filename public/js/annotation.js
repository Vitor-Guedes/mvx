function getUuid() {
    return Math.floor(Math.random() * (1000 - 1 + 1)) + 1;
};

let myTimeout = function (fn, tm = 500) {
    return window.setTimeout(fn, tm);
};

$evBus = new Vue({});

Vue.component('annotation', {
    template: `<div class="card-body" :id="data.id">
        <h5 class="card-title">
            <p v-if="label" v-on:dblclick="showInput"> {{ customerName }}</p>
            <input v-if="!label" v-model="customerName" v-on:focusout="showInput" type="text" class="input" placeholder="Name Customer" autofocus>
        </h5>
        <p class="card-text">
            <product-item v-for="item in data.productItems" :product="item" :key="item.itemId"></product-item>
        </p>
        <button class="btn btn-outline-dark btn-sm" v-on:click.self.prevent.stop="addProduct">Novo Item</button>
        <div class="row mt-2">
            <div class="col-6"> Total: R$ {{ total }} </div>
            <div class="col-6">
                <select id="status" class="form-select form-select-sm border border-0">
                    <option value="1"> Aberto </option>
                    <option value="1"> Pago </option>
                    <option value="1"> Fiado </option>
                </select>
            </div>
        </div>
    </div>`,
    props: ['data'],
    data () {
        return {
            total: 0,
            label: true,
            customerName: this.data.customerName
        }
    },
    created () {
        let that = this;

        $evBus.$on('updateProduct-' + that.data.id, function (product) {
            
            that.data.productItems.forEach((_product, index) => {
                if (_product.itemId == product.itemId) {
                    that.data.productItems[index] = product;
                }
            });

            that.total = 0;
            that.data.productItems.forEach(_product => {
                that.total += _product.total;
            });
        });
    },
    methods: {
        showInput: function (evt) {
            this.label = !this.label;
        },

        addProduct: function () {
            let id = getUuid();
            this.data.productItems.push({
                itemId: id,
                parentId: this.data.id,
                productItems: []
            });
        }
    },
});

Vue.component('product-item', {
    props: ['product'],

    data () {
        return {
            enable: true,
            products: [],
            total: 0
        }
    },

    created () {
        axios({
            method: 'post',
            url: '/product/names'
        }).then( response => {
            let fn = () => {
                this.products = response.data;

                this.product.price = this.products[0].price;
                this.product.quantity = 1;
                this.product.total = this.product.quantity * this.product.price;

                $evBus.$emit('updateProduct-' + this.product.parentId, this.product);
                this.total = this.product.total;

                this.enable = false;
            }

            myTimeout(fn);
        });
    },

    methods: {
        updatePrice: function (evt) {
            myTimeout(() => {
                this.product.price = evt.target.selectedOptions[0].getAttribute('data-price');
                this.product.total = this.product.price * this.product.quantity;

                this.product.total = parseFloat(this.product.total.toFixed(2));

                $evBus.$emit('updateProduct-' + this.product.parentId, this.product);
                this.total = this.product.total;
            });
        },

        updateQuantity: function (evt) {
            myTimeout(() => {
                this.product.total = this.product.quantity * this.product.price;

                this.product.total = parseFloat(this.product.total.toFixed(2));

                $evBus.$emit('updateProduct-' + this.product.parentId, this.product);
                this.total = this.product.total;
            });
        },
        onlyNumbers: function (evt) {
            let ev = evt || window.event;
            if (!(/[\d]+/.test(ev.key))) {
                ev.returnValue = false;
                ev.preventDefault();
            }
        }
    },
    template: `<div class="row mb-2" :id="product.itemId">
        <div class="col-6">
            <select :disabled="enable" id="productPrice" class="form-select form-select-sm" v-on:change="updatePrice($event)">
                <option v-for="(item, index) in products" :key="index" :value="item.id" :data-price="item.price" :data-id="item.id">{{ item.name }}</option> 
            </select>
        </div>
        <div class="col-3">
            <input :disabled="enable" class="form-control form-control-sm" type="text" v-model="product.quantity" v-on:change="updateQuantity" v-on:keypress="onlyNumbers">
        </div>
        <div class="col-3">R$ {{total}} </div>
    </div>`
});

document.addEventListener('DOMContentLoaded', function () {
    var app = new Vue({
        el: '#annotations',
        methods: {
            newMeetCustomer: function (evt) {
                evt.preventDefault();
                this.annotations.push({
                    id: getUuid(),
                    customerName: 'Nome do Cliente',
                    productItems: [],
                    total: 0
                })
            }
        },
        data () {
            return {
                annotations: []
            }
        }
    });
});