<template>
  <b-container class="mt-4">
    <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
    <b-row class="mb-3">
      <b-col>
        <b-button variant="success" @click="$router.push('/add-product')">Cadastrar Produto</b-button>
      </b-col>
      <b-col class="text-right">
        <b-form-input v-model="search" placeholder="Pesquisar pelo nome do produto"></b-form-input>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-table :items="filteredProducts" :fields="fields">
          <template #cell(type_name)="data">
            {{ data.item.typeName }}
          </template>
          <template #cell(actions)="data">
            <b-button size="sm" variant="primary" @click="openEditModal(data.item)">Editar</b-button>
            <b-button size="sm" variant="danger" @click="deleteProduct(data.item)">Deletar</b-button>
          </template>
        </b-table>
      </b-col>
    </b-row>

    <b-modal v-model="editModalVisible" title="Editar Produto" hide-footer>
      <b-form @submit.prevent="submitEdit">
        <b-form-group label="Nome do Produto">
          <b-form-input v-model="editProduct.name"></b-form-input>
        </b-form-group>
        <b-form-group label="Preço do Produto">
          <b-form-input v-model="editProduct.price" type="number" step="0.01"></b-form-input>
        </b-form-group>
        <b-form-group label="Tipo de Produto">
          <b-form-select v-model="editProduct.type_id" :options="productTypes"></b-form-select>
        </b-form-group>
        <b-button type="submit" variant="primary">Salvar Alterações</b-button>
        <b-button variant="danger" @click="editModalVisible = false">Cancelar</b-button>
      </b-form>
    </b-modal>
  </b-container>
</template>

<script>
import apiClient from '../axios';

export default {
  data() {
    return {
      products: [],
      productTypes: [],
      editProduct: { id: null, name: '', price: 0, type_id: null },
      editModalVisible: false,
      errorMessage: '',
      search: '',
      fields: [
        { key: 'name', label: 'Nome' },
        { key: 'price', label: 'Preço' },
        { key: 'type_name', label: 'Tipo de Produto' },
        { key: 'actions', label: 'Ações', sortable: false }
      ]
    };
  },
  computed: {
    filteredProducts() {
      return this.products.filter(product =>
        product.name.toLowerCase().includes(this.search.toLowerCase())
      );
    }
  },
  methods: {
    async fetchData() {
      await this.fetchProductTypes();
      await this.fetchProducts();
    },
    async fetchProductTypes() {
      try {
        const response = await apiClient.get('/product-type/view');
        this.productTypes = response.data.data.map(type => ({
          value: type.id, text: type.type_name
        }));
      } catch (error) {
        console.error('Erro ao buscar tipos de produtos:', error);
      }
    },
    async fetchProducts() {
      try {
        const response = await apiClient.get('/product/view');
        this.products = response.data.map(product => ({
          ...product,
          typeName: this.productTypes.find(type => type.value === product.type_id)?.text || 'Tipo não especificado'
        }));
      } catch (error) {
        console.error('Erro ao buscar produtos:', error);
      }
    },
    openEditModal(product) {
      this.editProduct = { ...product };
      this.editModalVisible = true;
    },
    submitEdit() {
      apiClient.put(`/product/edit?id=${this.editProduct.id}`, this.editProduct)
        .then(() => {
          this.fetchData();
          this.editModalVisible = false;
        })
        .catch(error => console.error('Erro ao editar produto:', error));
    },
    deleteProduct(product) {      
      apiClient.delete(`/product/delete?id=${product.id}`)
        .then(() => this.fetchData())
        .catch(error => {
          this.errorMessage = error.response.data.message;
        });
    }
  },
  mounted() {
    this.fetchData();
  }
};
</script>