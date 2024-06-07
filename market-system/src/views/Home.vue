<template>
    <b-container class="text-center mt-5 panel-container">
      <h1 class="panel-title">Painel do Operador</h1>
      <b-row class="mt-4">
        <b-col cols="12" md="4" class="mb-3">
          <b-button class="panel-button" variant="primary" @click="goTo('Sales')">
            Venda
          </b-button>
        </b-col>
        <b-col cols="12" md="4" class="mb-3">
          <b-button class="panel-button" variant="success" @click="goTo('Products')">
            Meus Produtos
          </b-button>
        </b-col>
        <b-col cols="12" md="4" class="mb-3">
          <b-button class="panel-button" variant="info" @click="goTo('ProductsType')">
            Tipos de Produtos
          </b-button>
        </b-col>
      </b-row>
    </b-container>
  </template>
  
  <script>
  export default {
    methods: {
      goTo(routeName) {
        this.$router.push({ name: routeName });
      }
    }
  };
  </script>
  
  <style scoped>
  .panel-container {
    padding: 40px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .panel-title {
    font-size: 36px;
    font-weight: bold;
    color: #343a40;
  }
  
  .panel-button {
    width: 100%;
    height: 60px;
    font-size: 18px;
    font-weight: bold;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .panel-button-primary {
    background-color: #007bff;
    border: none;
    color: white;
  }
  
  .panel-button-primary:hover {
    background-color: #0056b3;
  }
  
  .panel-button-success {
    background-color: #28a745;
    border: none;
    color: white;
  }
  
  .panel-button-success:hover {
    background-color: #218838;
  }
  
  .panel-button-info {
    background-color: #17a2b8;
    border: none;
    color: white;
  }
  
  .panel-button-info:hover {
    background-color: #117a8b;
  }
  </style>
  