<template>
  <table class="table table-dark">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th v-if="$can('edit_users') || $can('delete_users')" 
        scope="col" 
        class="text-center">Actions</th>
      </tr>
    </thead>
    <transition-group name="list" tag="tbody" v-if="fetchedData && !editFlag">
      <tr v-for="value in fetchedData" 
      :key="value.id"
      class="list-item">
        <th scope="row">{{ value.id }}</th>
        <td>{{ value.name }}</td>
        <td>{{ value.email }}</td>
        <td>{{ value.roles[0].name }}</td>
        <td v-if="$can('edit_users') || $can('delete_users')" 
        class="d-flex flex-row justify-content-center">
          <actions @edit="editItem(value.id)"
          @delete="deleteUser"
          :fetched-data="fetchedData"
          :confirm-title="'Delete User'"
          :confirm-body="'Permanently delete this user'"
          :confirm-body-item-no="`ID ${value.id}`"
          :value-id="value.id"
          :highlight-row-success-added="highlightRowSuccessUpdated">
            <slot v-if="$can('edit_users')" name="edit"></slot>
            <slot v-if="$can('delete_users')" name="delete"></slot>
          </actions>
        </td>
      </tr>
    </transition-group>
    <edit-user v-if="fetchedData && editFlag" 
    :fetched-data="fetchedData"
    :data-id="editID"
    :highlight-row="highlightRowSuccessUpdated"
    :highlight-row-error="highlightRowError">
      <template slot-scope="{ row, index }">
        <th scope="row">{{ row.id }}</th>
        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <input :id="`name-${row.id}`" 
          type="text" 
          v-model="editData[index].name" 
          class="form-control" 
          :name="`name-${row.id}`" 
          required 
          :autocomplete="`name-${row.id}`"
          autofocus>
          <div v-if="editID === errorID && nameError"
          class="container-error"
          :aria-errormessage="nameError">
            <span class="error">
              {{ nameError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.name }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <input :id="`email-${row.id}`" 
          type="email" 
          v-model="editData[index].email" 
          class="form-control" 
          :name="`email-${row.id}`" 
          required
          pattern="^([a-z0-9_\-\.]+)@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$" 
          :autocomplete="`email-${row.id}`"
          autofocus>
          <div v-if="editID === errorID && emailError"
          class="container-error"
          :aria-errormessage="emailError">
            <span class="error">
              {{ emailError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.email }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <Select2 v-model="editData[index].role"
          :options="roleOptions"
          :settings="{ placeholder: 'Select a role' }"
          :id="`role-${row.id}`"
          @change="myChangeEvent($event)" 
          @select="mySelectEvent($event)"
          required />
          <div v-if="editID === errorID && roleError"
          class="container-error"
          :aria-errormessage="roleError">
            <span class="error">
              {{ roleError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.roles[0].name }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)" 
        class="d-flex flex-row justify-content-center">
          <button v-if="disabled !== 'disabled'"
          @click="updateUser(editData[index], row.id)"
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
          class="btn btn-primary">Update</button>

          <button v-else
          @click="updateUser(editData[index], row.id)"
          disabled
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)"
          class="btn btn-primary">
            <font-awesome-icon 
            icon="spinner" 
            size="1x"
            spin fixed-width>
            </font-awesome-icon> Updating...
          </button>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)" 
        class="d-flex flex-row justify-content-center">
          <actions @edit="editItem(row.id)" 
          @delete="deleteUser"
          :fetched-data="fetchedData"
          :confirm-title="'Delete User'"
          :confirm-body="'Permanently delete this user'"
          :confirm-body-item-no="`ID ${row.id}`"
          :value-id="row.id"
          :highlight-row-success-added="highlightRowSuccessUpdated">
            <slot name="edit"></slot>
            <slot name="delete"></slot>
          </actions>
        </td>
      </template>
    </edit-user>
    <tbody v-else-if="!fetchedData && !editFlag">
      <tr>
        <th class="text-center" colspan="7">
          <font-awesome-icon 
          icon="spinner" 
          size="2x" 
          spin fixed-width>
          </font-awesome-icon> Loading...
        </th>
      </tr>
    </tbody>
  </table>
</template>

<script>
  import { APIService } from '../../services/APIService';
  import fieldAccessMixin from '../../mixins/fieldAccessMixin';

  const apiService = new APIService();

  export default {
    name: 'components-categories-list-component',
    mixins: [ fieldAccessMixin ],
    data: function() {
      return {
        editFlag: false,
        deleteFlag: false,
        editID: null,
        editUpdatedID: false, // Lock/unlock updated ID for editing
        roleOptions: null,
        errorID: null, // Current data ID that has validation error
        nameError: null,
        emailError: null,
        roleError: null,
        disabled: '',
        ariaDisabledReadOnly: false,
        highlightRowSuccessUpdated: false, // Row highlighting for updated data
        highlightRowError: false // Row highlighting for updating data with errors
      }
    },
    props: {
      fetchedData: {
        type: Array
      },
      editData: {
        type: Array
      },
      // Indicates that the specific record has been modified
      updatedId: {
        type: Number
      }
    },

    components: { 
      
    },

    methods: {
      editItem: function(value) {
        console.log(`Edit item :: ${value}`);
        this.editFlag = true;
        this.editID = value;
        // Clear the updated ID flag in unlocking each edit field
        (this.updatedId) ? this.unlockUpdatedID : '';
      },

      updateUser: function(modifiedData, id) {
        // e.preventDefault();
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;
        console.log(modifiedData);

        // Consume an API endpoint with a payload
        let payload = {
          name: modifiedData.name,
          email: modifiedData.email,
          password: modifiedData.password,
          role: modifiedData.role
        }

        apiService.updateData(this.$route.name, payload, id).then((res) => {
          if (typeof res.data.message.error === 'undefined') {
            console.log(res.data.message);
            // Validation errors
            this.nameError = null;
            this.emailError = null;
            this.roleError = null;
            // Reload the list
            console.log(`Updated ID :: ${id}`);
            this.$emit('updatedData', { id: id, obj: this.$store.dispatch('userUpdate', id) });
            // Lock each edit field
            this.editUpdatedID = false;
            // Row highlighting for updated data
            this.highlightRowSuccessUpdated = true;
            setTimeout(() => this.highlightRowSuccessUpdated = false, 1000);
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          } else {
            console.log(res.data.message.error);
            // Validation errors
            this.nameError = (!modifiedData.name || res.data.message.error.name) ? res.data.message.error.name[0] : null;
            this.emailError = (!modifiedData.email || res.data.message.error.email) ? res.data.message.error.email[0] : null;
            this.roleError = (!modifiedData.role || res.data.message.error.role) ? res.data.message.error.role[0] : null;
            // Row highlighting for updating data with errors
            this.highlightRowError = true;
            setTimeout(() => this.highlightRowError = false, 1000);
            this.errorID = id;
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          }
        }).catch((err) => {
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        });

      },

      deleteUser: function(id) {
        apiService.deleteData(this.$route.name, id).then((res) => {
          console.log(res.data.message);
          this.$emit('deleteId', id);
        }).catch((err) => {
          console.log(err);
        });
        
      },

      myChangeEvent: function(val) {
        console.log(val);
      },
      
      mySelectEvent: function({id, text}) {
        console.log({id, text})
      }
    },

    created() {
      this.$store.dispatch('roles').then(res => {
        this.roleOptions = res;
      });
    },

    mounted() {
      console.log(`editUpdatedID to ${this.editUpdatedID} for edit state.`);
    },

    computed: {
      unlockUpdatedID: function() {
        this.editUpdatedID = true;
        console.log(`In computed :: editUpdatedID to ${this.editUpdatedID} for edit state.`);
        return this.editUpdatedID;
      }
    }
  }
</script>
