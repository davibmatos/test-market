<template>
  <b-container class="sales-container">
    <b-row class="mb-3 mt-3">
      <b-col class="d-flex justify-content-end">
        <b-button variant="primary" @click="startNewSale">Criar Nova Venda</b-button>
      </b-col>
    </b-row>

    <b-table :items="sales" :fields="salesFields" class="mb-5">
      <template #cell(sale_date)="data">
        {{ $formatDate(data.item.sale_date) }}
      </template>      
    </b-table>

    <div v-if="newSaleViewVisible">
      <b-card>
        <h2 class="sales-header">Nova Venda</h2>
        <b-row>
          <b-col cols="8">
            <b-form-group>
              <multiselect v-model="selectedProduct" :options="filteredProducts" :searchable="true"
                :internal-search="false" :loading="isLoading" :options-limit="300"
                placeholder="Digite para buscar produtos" label="name" track-by="name"
                @search-change="fetchFilteredProducts"></multiselect>
            </b-form-group>
          </b-col>
          <b-col cols="2">
            <b-form-input class="sales-input mt-3" v-model="selectedQuantity" type="number" min="1"
              placeholder="Quantidade" />
          </b-col>
          <b-col cols="2">
            <b-button class="mt-3" @click="addProduct">Adicionar Produto</b-button>
          </b-col>
        </b-row>

        <b-table :fields="cartHeaders" :items="cart" class="mt-4">
          <template #cell(quantity)="data">
            <span>{{ data.item.quantity }}</span>
          </template>
          <template #cell(actions)="data">
            <b-button class="sales-button sales-button-danger" @click="removeProduct(data.item)">
              <b-icon icon="trash-fill"></b-icon> Deletar
            </b-button>
          </template>
        </b-table>

        <b-row class="sales-summary mt-3">
          <b-col>
            <div>Total de Impostos: {{ totalTaxes.toFixed(2) }}</div>
            <div>Total da Compra: {{ totalPrice.toFixed(2) }}</div>
            <b-button class="sales-button sales-button-primary mt-2" @click="finalizeSale">Finalizar Venda</b-button>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </b-container>
</template>

<script>
import Multiselect from 'vue-multiselect'
import apiClient from '../axios';

export default {
  components: { Multiselect },
  data() {
    return {
      products: [],
      sales: [],
      cart: [],
      selectedProduct: null,
      selectedQuantity: 1,
      searchQuery: '',
      newSaleViewVisible: false,
      isLoading: false,
      salesFields: [
        { key: 'id', label: 'ID' },
        { key: 'total', label: 'Total da Compra' },
        { key: 'taxes', label: 'Total de Impostos' },
        { key: 'sale_date', label: 'Data da Venda' },
      ],
      cartHeaders: [
        { key: 'name', label: 'Produto' },
        { key: 'quantity', label: 'Quantidade' },
        { key: 'price', label: 'Preço Unitário' },
        { key: 'subtotal', label: 'Subtotal' },
        { key: 'tax_rate', label: 'Imposto' },
        { key: 'actions', label: 'Ações' }
      ]
    };
  },
  computed: {
    totalTaxes() {
      return this.cart.reduce((sum, item) => sum + item.tax, 0);
    },
    totalPrice() {
      return this.cart.reduce((sum, item) => sum + item.subtotal, 0);
    },
    filteredProducts() {
      return this.products;
    }
  },
  methods: {
    fetchProducts() {
      this.isLoading = true;
      apiClient.get('/product/view')
        .then(response => {
          console.log("Produtos carregados:", response.data);
          this.products = response.data.map(product => ({
            ...product,
            taxRate: product.taxRate || 0
          }));
          this.isLoading = false;
        })
        .catch(error => {
          console.error('Erro ao buscar produtos:', error);
          this.isLoading = false;
        });
    },
    fetchSales() {
      apiClient.get('/sale/view')
        .then(response => {
          this.sales = response.data.data;
        })
        .catch(error => console.error('Erro ao buscar vendas:', error));
    },
    startNewSale() {
      this.newSaleViewVisible = true;
      this.cart = [];
      this.selectedProduct = null;
      this.selectedQuantity = 1;
      this.searchQuery = '';
      this.fetchProducts();
    },
    fetchFilteredProducts(query) {
      if (query.length > 2) {
        apiClient.get(`/product/search?query=${query}`)
          .then(response => {
            this.products = response.data;
          })
          .catch(error => console.error('Erro ao filtrar produtos:', error));
      }
    },
    addProduct() {
      if (!this.selectedProduct || this.cart.some(item => item.id === this.selectedProduct.id)) {
        return;
      }
      const product = this.products.find(p => p.id === this.selectedProduct.id);
      const item = {
        ...product,
        quantity: this.selectedQuantity,
        subtotal: product.price * this.selectedQuantity,
        tax: (product.tax_rate / 100) * product.price * this.selectedQuantity
      };
      this.cart.push(item);
      this.selectedProduct = null;
      this.selectedQuantity = 1;
    },
    updateQuantity(item) {
      item.subtotal = item.quantity * item.price;
      item.tax = (item.taxRate / 100) * item.subtotal;
    },
    removeProduct(product) {
      this.cart = this.cart.filter(item => item.id !== product.id);
    },
    finalizeSale() {
      const sale = {
        cart: this.cart,
        total: this.totalPrice,
        taxes: this.totalTaxes,
        date: new Date().toISOString()
      };
      apiClient.post('/sale/add', sale)
        .then(() => {
          this.fetchSales();
          this.newSaleViewVisible = false;
        })
        .catch(error => console.error('Erro ao finalizar venda:', error));
    },
    viewSale(sale) {
      console.log('Ver detalhes da venda:', sale);
    },
    deleteSale(sale) {
      apiClient.delete(`/sales/delete?id=${sale.id}`)
        .then(() => {
          this.fetchSales();
        })
        .catch(error => console.error('Erro ao deletar venda:', error));
    }
  },
  mounted() {
    this.fetchProducts();
    this.fetchSales();
  }
};
</script>

<style scoped>
.sales-container {
  margin-top: 20px;
}

.sales-header {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

.sales-select,
.sales-input {
  width: 100%;
}

.sales-summary {
  font-size: 18px;
}

.sales-button {
  width: 100%;
  padding: 10px;
}

.sales-button-primary {
  background-color: #007bff;
  color: white;
}

.sales-button-primary:hover {
  background-color: #0056b3;
}

.sales-button-danger {
  background-color: #dc3545;
  color: white;
}

.sales-button-danger:hover {
  background-color: #c82333;
}
</style>
