<template>
    <b-container>
      <b-row class="mb-3 mt-3">
        <b-col>
          <b-button variant="success" @click="openAddModal">Novo Cadastro</b-button>
        </b-col>
        <b-col class="text-right">
          <b-form-input
            v-model="search"
            placeholder="Pesquisar pelo nome"
          ></b-form-input>
        </b-col>
      </b-row>
      <b-table :items="filteredProductTypes" :fields="fields">
        <template #cell(edit)="data">
          <b-button variant="info" @click="openEditModal(data.item)">Editar</b-button>
        </template>
        <template #cell(delete)="data">
          <b-button variant="danger" @click="deleteProductType(data.item)">Deletar</b-button>
        </template>
      </b-table>
  
      <b-modal v-model="editModalVisible" title="Editar Tipo de Produto" hide-footer>
        <b-form @submit.prevent="submitEdit">
          <b-form-group label="Nome do Tipo">
            <b-form-input v-model="editProductType.type_name"></b-form-input>
          </b-form-group>
          <b-form-group label="Percentual de Imposto">
            <b-form-input v-model="editProductType.tax_rate" type="number" step="0.01" required></b-form-input>
          </b-form-group>
          <b-button type="submit" variant="primary">Salvar Alterações</b-button>
          <b-button variant="danger" @click="editModalVisible = false">Cancelar</b-button>
        </b-form>
      </b-modal>
  
      <b-modal v-model="addModalVisible" title="Cadastrar Novo Tipo de Produto" hide-footer>
        <b-form @submit.prevent="submitAdd">
          <b-form-group label="Nome do Tipo">
            <b-form-input v-model="newProductType.type_name"></b-form-input>
          </b-form-group>
          <b-form-group label="Percentual de Imposto">
            <b-form-input v-model="newProductType.tax_rate" type="number" step="0.01"></b-form-input>
          </b-form-group>
          <b-button type="submit" variant="primary">Cadastrar</b-button>
          <b-button variant="danger" @click="addModalVisible = false">Cancelar</b-button>
        </b-form>
      </b-modal>
    </b-container>
  </template>  
  
  <script>
import apiClient from '../axios';

export default {
  data() {
    return {
      productTypes: [],
      editProductType: {},
      newProductType: {
        type_name: '',
        tax_rate: 0
      },
      editModalVisible: false,
      addModalVisible: false,
      search: '',
      fields: [
        { key: 'type_name', label: 'Nome' },
        { key: 'tax_rate', label: 'Imposto' },
        { key: 'edit', label: 'Editar' },
        { key: 'delete', label: 'Deletar' }
      ]
    };
  },
  computed: {
    filteredProductTypes() {
      return this.productTypes.filter(type =>
        type.type_name.toLowerCase().includes(this.search.toLowerCase())
      );
    }
  },
  methods: {
    fetchProductTypes() {
      apiClient.get('/product-type/view')
        .then(response => {
          console.log("Dados recebidos:", response.data.data);
          this.productTypes = response.data.data;
        })
        .catch(error => console.error('Erro ao carregar tipos de produtos:', error));
    },
    openEditModal(item) {
      console.log("Abrindo modal para editar:", item);
      this.editProductType = { ...item };
      this.editModalVisible = true;
    },
    submitEdit() {
      console.log("Submetendo edição para:", this.editProductType);
      apiClient.put(`/product-type/edit?id=${this.editProductType.id}`, this.editProductType)
        .then(() => {
          this.fetchProductTypes();
          this.editModalVisible = false;
        })
        .catch(error => console.error('Erro ao editar tipo de produto:', error));
    },
    openAddModal() {
      this.newProductType = { type_name: '', tax_rate: 0 };
      this.addModalVisible = true;
    },
    submitAdd() {
      console.log("Submetendo novo tipo de produto:", this.newProductType);
      apiClient.post('/product-type/add', this.newProductType)
        .then(() => {
          this.fetchProductTypes();
          this.addModalVisible = false;
        })
        .catch(error => console.error('Erro ao cadastrar tipo de produto:', error));
    },
    deleteProductType(item) {
      apiClient.delete(`/product-type/delete?id=${item.id}`)
        .then(() => {
          this.fetchProductTypes();
        })
        .catch(error => console.error('Erro ao deletar tipo de produto:', error));
    }
  },
  mounted() {
    this.fetchProductTypes();
  }
};
</script>