<template>
  <table class="table table-dark">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Type</th>
        <th scope="col">Permissions</th>
        <th v-if="$can('edit_roles') || $can('delete_roles')"
        scope="col" 
        class="text-center">Actions</th>
      </tr>
    </thead>
    <transition-group name="list" tag="tbody" v-if="fetchedData && !editFlag">
      <tr v-for="value in fetchedData" 
      :key="value.id"
      :class="{ 'list-enter-active': highlightRowSuccessAdded }" 
      class="list-item">
        <th scope="row">{{ value.id }}</th>
        <td>{{ value.name }}</td>
        <td>
          <div class="d-flex flex-wrap">
            <div v-for="val in value.get_permissions" 
            :key="val.id"
            class="permissions px-3">
              {{ val.name | underscoreToSpace | capitalize }}
            </div>
          </div>
        </td>
        <td v-if="$can('edit_roles') || $can('delete_roles')"
        class="d-flex flex-row justify-content-center">
          <actions @edit="editItem(value.id)" 
          @delete="deleteRole"
          :confirm-title="'Delete Role'"
          :confirm-body="'Permanently delete this role'"
          :confirm-body-item-no="`ID ${value.id}`"
          :value-id="value.id">
            <slot v-if="$can('edit_roles')" name="edit"></slot>
            <slot v-if="$can('delete_roles')" name="delete"></slot>
          </actions>
        </td>
      </tr>
    </transition-group>
    <edit-role v-if="fetchedData && editFlag" 
    :fetched-data="fetchedData"
    :added-id="addedId"
    :data-id="editID"
    :highlight-row-success-added="highlightRowSuccessAdded"
    :highlight-row="highlightRowSuccessUpdated"
    :highlight-row-error="highlightRowError">
      <template slot-scope="{ row, index }">
        <th scope="row">{{ row.id }}</th>
        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <input :id="`name-${row.id}`" 
          type="text"
          v-model="checkRole[row.id]"
          class="form-control" 
          :name="`name-${row.id}`" 
          required 
          :autocomplete="`name-${row.id}`"
          v-focus>
          <div v-if="editID === errorID && typeError"
          class="container-error"
          :aria-errormessage="typeError">
            <span class="error">
              {{ typeError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.name }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <div class="card-body">
            <div class="form-check">
              <!-- 1st ROW -->
              <div class="row">
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.view_roles"
                  type="checkbox"
                  id="view-roles"
                  v-model="checkPermission[index]"><span>View roles</span>
                
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.create_roles"
                  type="checkbox"
                  id="create-roles"
                  v-model="checkPermission[index]"><span>Create roles</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.edit_roles"
                  type="checkbox"
                  id="edit-roles"
                  v-model="checkPermission[index]"><span>Edit roles</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.delete_roles"
                  type="checkbox"
                  id="delete-roles"
                  v-model="checkPermission[index]"><span>Delete roles</span>
                </div>
              </div>
              <!-- END 1st ROW -->
              <!-- 2ND ROW -->
              <div class="row">
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.view_users"
                  type="checkbox"
                  id="view-users"
                  v-model="checkPermission[index]"><span>View users</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.create_users"
                  type="checkbox"
                  id="create-users"
                  v-model="checkPermission[index]"><span>Create users</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.edit_users"
                  type="checkbox"
                  id="edit-users"
                  v-model="checkPermission[index]"><span>Edit users</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.delete_users"
                  type="checkbox"
                  id="delete-users"
                  v-model="checkPermission[index]"><span>Delete users</span>
                </div>
              </div>
              <!-- END 2ND ROW -->
              <!-- 3RD ROW -->
              <div class="row">
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.view_category"
                  type="checkbox"
                  id="view-category"
                  v-model="checkPermission[index]"><span>View category</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.create_category"
                  type="checkbox"
                  id="create-category"
                  v-model="checkPermission[index]"><span>Create category</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.edit_category"
                  type="checkbox"
                  id="edit-category"
                  v-model="checkPermission[index]"><span>Edit category</span>
                </div>
                <div v-if="!editData[index].name || permissionAccess[row.id]" class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="adminConfigPermissions.delete_category"
                  type="checkbox"
                  id="delete-category"
                  v-model="checkPermission[index]"><span>Delete category</span>
                </div>
              </div>
              <!-- END 3RD ROW -->
              <!-- 4TH ROW -->
              <div class="row">
                <div class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="usersConfigPermissions.view_expenses"
                  type="checkbox"
                  id="view-expenses"
                  v-model="checkPermission[index]"><span>View expenses</span>
                </div>
                <div class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="usersConfigPermissions.create_expenses"
                  type="checkbox"
                  id="create-expenses"
                  v-model="checkPermission[index]"><span>Create expenses</span>
                </div>
                <div class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="usersConfigPermissions.edit_expenses"
                  type="checkbox"
                  id="edit-expenses"
                  v-model="checkPermission[index]"><span>Edit expenses</span>
                </div>
                <div class="col-md-3">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="usersConfigPermissions.delete_expenses"
                  type="checkbox"
                  id="delete-expenses"
                  v-model="checkPermission[index]"><span>Delete expenses</span>
                </div>
              </div>
              <!-- END 4TH ROW -->
              <!-- 5TH ROW -->
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <!-- THIS IS ALREADY A CHECKED STATE OPTIONAL TO MODIFY -->
                  <input :value="usersConfigPermissions.edit_password"
                  type="checkbox"
                  id="edit-password"
                  v-model="checkPermission[index]"><span>Edit password</span>
                </div>
              </div>
              <!-- END 5TH ROW -->
              <div v-if="editID === errorID && permissionsError"
              class="row">
                <div class="col">
                  <div class="container-error"
                  :aria-errormessage="permissionsError">
                    <span class="error">
                      {{ permissionsError }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          <div class="d-flex flex-wrap">
            <div v-for="val in row.get_permissions" 
            :key="val.id"
            class="permissions px-3">
              {{ val.name | underscoreToSpace | capitalize }}
            </div>
          </div>
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)" 
        class="d-flex flex-row justify-content-center">
          <button v-if="disabled !== 'disabled'"
          @click="updateRole(checkRole[row.id], row.id, permissionAccess[row.id])"
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
          class="btn btn-primary">Update</button>

          <button v-else
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
          @delete="deleteRole"
          :confirm-title="'Delete Role'"
          :confirm-body="'Permanently delete this role'"
          :confirm-body-item-no="`ID ${row.id}`"
          :value-id="row.id">
            <slot name="edit"></slot>
            <slot name="delete"></slot>
          </actions>
        </td>
      </template>
    </edit-role>
    <tbody v-else-if="!fetchedData">
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
  import objMixin from '../../mixins/objMixin';

  const apiService = new APIService();

  export default {
    name: 'components-roles-list-component',
    mixins: [ objMixin ],
    data: function() {
      return {
        nameValue: [],
        editFlag: false, // List of data subject for modification
        editID: null, // Current data ID to modify
        editUpdatedID: false, // Lock/unlock updated ID for editing
        permissionAccess: [],
        errorID: null, // Current data ID that has validation error
        typeError: null,
        permissionsError: null,
        permissionsErrorCustomText: 'The permissions field is required.', 
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
      addedId: {
        type: Number
      },
      editData: {
        type: Array
      },
      // Indicates that the specific record has been modified
      updatedId: {
        type: Number
      },
      permissions: {
        type: Array
      },
      adminConfigPermissions: {
        type: Object
      },
      usersConfigPermissions: {
        type: Object
      },
      highlightRowSuccessAdded: {
        type: Boolean
      }
    },

    components: { 
      
    },

    methods: {
      loadComplete: function() {
        console.log('Data has been loaded completely.');
      },

      editItem: function(value) {
        console.log(`Edit item :: ${value}`);
        this.editFlag = true;
        this.editID = value;
        // Clear the updated ID flag in unlocking each edit field
        (this.updatedId) ? this.unlockUpdatedID : '';
      },

      updateRole: function(name, id, isAdmin) {
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;

        // If the role is not admin, admin permissions should be disabled
        this.permissions[id] = (!isAdmin) 
          ? _.filter(this.permissions[id], (obj) => {
            return !_.values(this.adminConfigPermissions).includes(obj)
              && _.values(this.usersConfigPermissions).includes(obj)
          })
          : this.permissions[id];

        // Check if the permission/s has/have been selected, update the permissions data
        let selectedPermissions = JSON.stringify(this.checkboxValProcess({}, this.permissions[id], this));
        
        // Consume an API endpoint with a payload
        let payload = {
          name: name,
          permission: selectedPermissions
        }

        // Validation errors
        // Validate empty permissions object
        // Should not accept an empty permission object
        if (selectedPermissions === JSON.stringify({})) {
          this.permissionsError = this.permissionsErrorCustomText;
          console.log(this.permissions);
          // Row highlighting for updating data with errors
          this.highlightRowError = true;
          setTimeout(() => this.highlightRowError = false, 1000);
          this.errorID = id;
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        } else {
          apiService.updateData(this.$route.name, payload, id).then((res) => {
            console.log(res.data.message);
            // Validation errors
            this.typeError = null;
            this.permissionsError = null;
            // Reload the list
            console.log(`Updated ID :: ${id}`);
            this.$emit('updatedData', { id: id, obj: this.$store.dispatch('roleUpdate', id) });
            // Lock each edit field
            this.editUpdatedID = false;
            // Row highlighting for updated data
            this.highlightRowSuccessUpdated = true;
            setTimeout(() => this.highlightRowSuccessUpdated = false, 1000);
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
            
          }).catch((err) => {
            // console.log(Promise.reject(err.response.data.errors.name[0]));
            // Validation errors
            this.typeError = (!name || err.response.data.errors.name) ? err.response.data.errors.name[0] : null;
            this.permissionsError = (!this.permissions || err.response.data.errors.permission) 
              ? err.response.data.errors.permission[0] 
              : (JSON.stringify(this.permissions) === JSON.stringify({}))
                ? this.permissionsErrorCustomText
                : null;
            console.log(this.permissions);
            // Row highlighting for updating data with errors
            this.highlightRowError = true;
            setTimeout(() => this.highlightRowError = false, 1000);
            this.errorID = id;
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          });
        }
      },

      deleteRole: function(id) {
        apiService.deleteData(this.$route.name, id).then((res) => {
          console.log(res.data.message);
          this.$emit('deleteId', id);
        }).catch((err) => {
          console.log(err);
        });
        
      },

    },

    created() {
      
    },

    mounted() {
     
    },

    computed: {
      unlockUpdatedID: function() {
        this.editUpdatedID = true;
        console.log(`In computed :: editUpdatedID to ${this.editUpdatedID} for edit state.`);
        return this.editUpdatedID;
      },

      checkRole: {
        get: function() {
          // When the autofocus comes into play, check for current role name to be modified
          console.log(this.nameValue);
          if (this.nameValue.length === 0 || !this.nameValue[this.editID]) 
            this.nameValue[this.editID] = this.editData[this.editID].name
          
          // Clear up the role name value for the new entry
          this.nameValue[this.editID] = (this.nameValue[this.editID].length === 1)
            ? ' ' : this.nameValue[this.editID].trim();
          
          this.permissionAccess[this.editID] = this.roleAccess(this.nameValue[this.editID], 'admin') ? true : false;
          
          console.log(this.permissionAccess[this.editID]);

          console.log('----Check role----');
          console.log(this.permissions[this.editID]);

          return this.nameValue;
        },
        set: function(value) {
          this.nameValue = value;
        }
      },

      checkPermission: {
        get: function() {
          this.permissions[this.editID] = _.filter(this.permissions[this.editID], (obj) => {
            return _.values(this.adminConfigPermissions).includes(obj)
            || _.values(this.usersConfigPermissions).includes(obj)
          });
          
          console.log('----Check permission----');
          console.log(this.permissions[this.editID]);

          return this.permissions;
        },
        set: function(value) {
          this.permissions = value;
        }
      }
    },

    watch: {
      fetchedData: 'loadComplete'
    }
  }
</script>
