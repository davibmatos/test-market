<template>
  <b-container>
    <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
    <b-row class="mb-3 mt-3">
      <b-col>
        <b-button variant="success" @click="openAddModal">Novo Cadastro</b-button>
      </b-col>
      <b-col class="text-right">
        <b-form-input v-model="search" placeholder="Pesquisar pelo nome"></b-form-input>
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
      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
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
      newProductType: { type_name: '', tax_rate: 0 },
      editModalVisible: false,
      addModalVisible: false,
      errorMessage: '',
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
          this.productTypes = response.data.data;
        })
        .catch(error => console.error('Erro ao carregar tipos de produtos:', error));
    },
    openEditModal(item) {
      this.editProductType = { ...item };
      this.editModalVisible = true;
    },
    submitEdit() {
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
      this.errorMessage = '';
      apiClient.post('/product-type/add', this.newProductType)
        .then(response => {
          if (response.data.status === "success") {
            this.productTypes.push(response.data.data);
            this.addModalVisible = false;
            this.fetchProductTypes();
          } else {
            this.errorMessage = response.data.message;
          }
        })
        .catch(error => {
          this.errorMessage = error.response.data.message;
        });
    },
    deleteProductType(item) {
      apiClient.delete(`/product-type/delete?id=${item.id}`)
        .then(() => {
          this.fetchProductTypes();
        })
        .catch(error => {
          this.errorMessage = error.response.data.message;
        });
    }
  },
  mounted() {
    this.fetchProductTypes();
  }
};
</script>