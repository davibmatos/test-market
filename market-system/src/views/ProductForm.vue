<template>
  <b-container class="form-container">
    <b-form @submit.prevent="submitProduct">
      <b-form-group label="Nome do Produto" class="form-group">
        <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
        <b-form-input class="form-input" v-model="product.name" required></b-form-input>
      </b-form-group>
      <b-form-group label="Preço do Produto" class="form-group">
        <b-form-input class="form-input" v-model="product.price" type="number" step="0.01" required></b-form-input>
      </b-form-group>
      <b-form-group label="Estoque" class="form-group">
        <b-form-input class="form-input" v-model="product.stock" type="number" min="0" required></b-form-input>
      </b-form-group>
      <b-form-group label="Tipo de Produto" class="form-group">
        <div class="d-flex align-items-center">
          <b-form-select class="form-select" v-model="product.type" :options="productTypes"></b-form-select>
          <b-button variant="outline-secondary" @click="openTypeModal" class="ml-2">
            <b-icon icon="plus"></b-icon>+
          </b-button>
        </div>
      </b-form-group>
      <b-button class="submit-button" variant="primary" type="submit">Registrar Produto</b-button>
    </b-form>

    <b-modal v-model="typeModalOpen" title="Cadastro de Novo Tipo de Produto">
      <b-form-group label="Nome do Tipo" class="form-group">
        <b-form-input class="form-input" v-model="newType.name" required></b-form-input>
      </b-form-group>
      <b-form-group label="Percentual de Imposto" class="form-group">
        <b-form-input class="form-input" v-model="newType.taxRate" type="number" step="0.01" required></b-form-input>
      </b-form-group>
      <template #modal-footer="{ close }">
        <b-button class="button-cancel" variant="secondary" @click="closeTypeModal">Cancelar</b-button>
        <b-button class="button-save" variant="primary" @click="saveType">Salvar</b-button>
      </template>
    </b-modal>
  </b-container>
</template>

<script>
import apiClient from '../axios';
import "../assets/styles.css";
import { useRouter } from 'vue-router';

export default {
  data() {
    return {
      product: { name: '', price: 0, type: null, stock: 0 },
      productTypes: [],
      newType: { name: '', taxRate: 0 },
      typeModalOpen: false,
      errorMessage: '',
    };
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  methods: {
    openTypeModal() {
      this.typeModalOpen = true;
    },
    closeTypeModal() {
      this.typeModalOpen = false;
    },
    saveType() {
      apiClient.post('/product-type/add', this.newType)
        .then(response => {
          this.productTypes.push({ value: response.data.id, text: response.data.type_name });
          this.closeTypeModal();
        })
        .catch(error => console.error('Erro ao salvar tipo de produto:', error));
    },
    submitProduct() {
      apiClient.post('/product/add', this.product)
        .then(() => {
          this.router.push('/products');
        })
        .catch(error => {
          this.errorMessage = error.response.data.message;        
        });
    }
  },
  mounted() {
    apiClient.get('/product-type/view')
      .then(response => {
        this.productTypes = response.data.data.map(type => ({ value: type.id, text: type.type_name }));
      })
      .catch(error => console.error('Erro ao carregar tipos de produtos:', error));
  }
};
</script>