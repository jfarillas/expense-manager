import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import { APIService } from '../services/APIService';

const apiService = new APIService();
const defaultState = () => {
  return [];
};

Vue.use(Vuex);

export default new Vuex.Store({
  plugins: [createPersistedState()],
  
  state: {
    appName: null,
    user: {
      loggedIn: false,
      users: [],
      userUpdate: null,
      roles: {},
      rolesList: [],
      roleNew: null,
      roleUpdate: null,
      tempPermissionsSingle: null,
      tempPermissions: null
    },
    categories: {},
    categoryList: [],
    categoryNew: null,
    categoryUpdate: null,
    expenseId: null,
    expenseNew: null,
    expenseUpdate: null,
    expenses: [],
    expenseType: [],
    expensesPerCategory: [],
    message: null
  },
  
  mutations: {
    appName (state, payload) {
      state.appName = payload;
    },

    loggedIn (state, payload) {
      state.user.loggedIn = payload;
    },

    users(state, payload) {
      console.log('----Users Mutation for all data----');
      console.log(payload);
      state.user.users = payload;
    },

    userUpdate(state, payload) {
      console.log('----Users Mutation for updated data----');
      console.log(payload);
      state.user.userUpdate = payload;
    },

    roles(state, payload) {
      console.log('----Roles Mutation for all data----');
      console.log(payload);
      state.user.roles = payload;
    },

    rolesList(state, payload) {
      console.log('----Roles Mutation for all data----');
      console.log(payload);
      state.user.rolesList = payload;
    },

    roleNew(state, payload) {
      console.log('----Roles Mutation for added data----');
      console.log(payload);
      state.user.roleNew = payload;
    },

    roleUpdate(state, payload) {
      console.log('----Role Mutation for updated data----');
      console.log(payload);
      state.user.roleUpdate = payload;
    },

    tempPermissionsSingle(state, payload) {
      if (payload.length > 0)
        _.keys(payload).forEach(index => {
          // Delete the old permission/s
          Vue.delete(state.user.tempPermissionsSingle, index);
          // Append new selected permission/s
          Vue.set(state.user.tempPermissionsSingle, index, payload[index]);
        });
        
      // Clear state - for debugging purposes
      //state.user.tempPermissionsSingle = [];

      console.log('----Mutation for updating permission----');
      console.log(state.user.tempPermissionsSingle);
    },

    tempPermissions(state, payload) {
      if (payload.length > 0)
        _.keys(payload).forEach(index => {
          // Delete the old permission/s
          Vue.delete(state.user.tempPermissions, index);
          // Append new selected permission/s
          Vue.set(state.user.tempPermissions, index, payload[index]);
        });

      // Clear state - for debugging purposes
      //state.user.tempPermissions = [];

      console.log('----Mutation for updating permission----');
      console.log(state.user.tempPermissions);
    },

    categories (state, payload) {
      state.categories = payload;
    },

    categoryNew(state, payload) {
      console.log('----Categories Mutation for added data----');
      console.log(payload);
      state.categoryNew = payload;
    },

    categoryUpdate(state, payload) {
      console.log('----Categories Mutation for updated data----');
      console.log(payload);
      state.categoryUpdate = payload;
    },

    categoryList(state, payload) {
      console.log('----Categories Mutation for all data----');
      console.log(payload);
      state.categoryList = payload;
    },

    expenseId(state, payload) {
      state.expenseId = payload;
    },

    expenseNew(state, payload) {
      console.log('----Expenses Mutation for added data----');
      console.log(payload);
      state.expenseNew = payload;
    },

    expenseUpdate(state, payload) {
      console.log('----Expenses Mutation for updated data----');
      console.log(payload);
      state.expenseUpdate = payload;
    },

    expenses(state, payload) {
      console.log('----Expenses Mutation for all data----');
      console.log(payload);
      state.expenses = payload;
    },
    
    expenseType (state, payload) {
      state.expenseType = payload;
    },

    expensesPerCategory (state, payload) {
      state.expensesPerCategory = payload;
    },

    message (state, payload) {
      state.message = payload;
    },
    
    resetPermissionsState (state, payload) {
      console.log('----Reset Permission State Mutation----');
      // Merge rather than replace so we don't lose observers
      // https://github.com/vuejs/vuex/issues/1118
      //Object.assign(state, getDefaultState())
      // if (payload.length > 0)
      //   state.user.tempPermissionsSingle = defaultState();
    }

  },

  actions: {
    appName (context, payload) {
      context.commit('appName', payload);
    },

    loggedIn (context, payload) {
      context.commit('loggedIn', payload);
    },

    users (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('users', id).then((res) => {
          let data = res.data || res;
          _.reverse(data);
          console.log('----Users Action for all data----');
          console.log(data);
          context.commit('users', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    userUpdate (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('users', payload).then((res) => {
          let data = res.data || res;
          console.log('----Users Action for updated data----');
          console.log(data);
          context.commit('userUpdate', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    rolesList (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('roles', id).then((res) => {
          let data = res.data || res;
          _.reverse(data);
          console.log('----Roles Action for all data----');
          console.log(data);
          context.commit('rolesList', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    roles (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('roles', id).then((res) => {
          let data = res.data || res;
          let wrapped = [];
          _.reverse(data);
          console.log('----Roles Action for all data----');
          console.log(data);

          _.keys(data).forEach(index => {
            _(wrapped)
            .push({
              id: data[index].name,
              text: data[index].name
            })
            .value();
          });
          context.commit('roles', wrapped);
          resolve(wrapped);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    roleNew (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('roles', payload).then((res) => {
          let data = res.data || res;
          console.log('----Roles Action for added data----');
          console.log(data);
          context.commit('roleNew', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    roleUpdate (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('roles', payload).then((res) => {
          let data = res.data || res;
          console.log('----Role Action for updated data----');
          console.log(data);
          context.commit('roleUpdate', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    tempPermissionsSingle (context, payload) {
      context.commit('tempPermissionsSingle', payload);
    },

    tempPermissions (context, payload) {
      context.commit('tempPermissions', payload);
    },

    categories (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('categories', id).then((res) => {
          let data = res.data || res;
          let wrapped = [];
          _.reverse(data);
          console.log('----Categories Action for all data----');
          console.log(data);

          _.keys(data).forEach(index => {
            _(wrapped)
            .push({
              id: data[index].id,
              text: data[index].type
            })
            .value();
          });
          context.commit('categories', wrapped);
          resolve(wrapped);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    categoryNew (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('categories', payload).then((res) => {
          let data = res.data || res;
          console.log('----Categories Action for added data----');
          console.log(data);
          context.commit('categoryNew', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    categoryUpdate (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('categories', payload).then((res) => {
          let data = res.data || res;
          console.log('----Categories Action for updated data----');
          console.log(data);
          context.commit('categoryUpdate', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    categoryList (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('categories', id).then((res) => {
          let data = res.data || res;
          _.reverse(data);
          console.log('----Categories Action for all data----');
          console.log(data);
          context.commit('categoryList', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
      
    },

    expenseId (context, payload) {
      context.commit('expenseId', payload);
    },

    expenseNew (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('expenses', payload).then((res) => {
          let data = res.data || res;
          console.log('----Expenses Action for added data----');
          console.log(data);
          context.commit('expenseNew', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    expenseUpdate (context, payload) {
      return new Promise((resolve, reject) => {
        apiService.getData('expenses', payload).then((res) => {
          let data = res.data || res;
          console.log('----Expenses Action for updated data----');
          console.log(data);
          context.commit('expenseUpdate', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    expenses (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('expenses', id).then((res) => {
          let data = res.data || res;
          _.reverse(data);
          console.log('----Expenses Action for all data----');
          console.log(data);
          context.commit('expenses', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },

    expenseType (context, payload) {
      context.commit('expenseType', payload);
    },

    expensesPerCategory (context, payload) {
      return new Promise((resolve, reject) => {
        const id = null;
        apiService.getData('expenses-count', id).then((res) => {
          let data = res.data || res;
          _.reverse(data);
          console.log('----Expenses Per Category Action for all data----');
          console.log(data);
          context.commit('expensesPerCategory', data);
          resolve(data);
        }).catch((err) => {
          console.log(err);
          reject(err);
        });
      });
    },
    
    message (context, payload) {
      context.commit('message', payload);
    },
    
    resetPermissionsState (context, payload) {
      console.log('----Reset Permissions State Action----');
      context.commit('resetPermissionsState', payload);
    },
  },

  getters: {
    appName: state => {
      return state.appName;
    },

    loggedIn: state => {
      return state.user.loggedIn;
    },

    roles: state => {
      return state.user.roles;
    },

    rolesList: state => {
      return state.user.rolesList;
    },

    tempPermissionsSingle: state => {
      return state.user.tempPermissionsSingle;
    },

    tempPermissions: state => {
      return state.user.tempPermissions;
    },

    categories: state => {
      return state.categories;
    },

    categoryList: state => {
      return state.categoryList;
    },

    expenses: state => {
      return state.expenses;
    },

    expenseType: state => {
      return state.expenseType;
    },

    expensesPerCategory: state => {
      return state.expensesPerCategory;
    },

    message: state => {
      return state.message;
    }
  }

})
