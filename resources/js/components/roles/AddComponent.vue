<template>
  <form class="block__form">
    <div class="form-group d-flex flex-row">
      <label for="name" class="col-form-label text-md-left">Type</label>
      <div>
        <input id="name" 
        type="text" 
        v-model="checkRole" 
        class="form-control" 
        name="name" 
        required 
        autocomplete="name" 
        autofocus>
        <span v-if="typeError" 
        class="error" 
        :aria-errormessage="typeError">
          {{ typeError }}
        </span>
      </div>
    </div>
    <div class="form-group row">
      <div class="card block__form--permission">
        <div class="card-header">
          <h6>Permissions</h6>
        </div>
        <div class="card-body">
          <div class="form-check">
            <!-- 1st ROW -->
            <div class="row">
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.view_roles"
                type="checkbox"
                id="view-roles"
                v-model="checkPermission"><span>View roles</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.create_roles"
                  type="checkbox"
                  id="create-roles"
                  v-model="checkPermission"><span>Create roles</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.edit_roles"
                  type="checkbox"
                  id="edit-roles"
                  v-model="checkPermission"><span>Edit roles</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.delete_roles"
                  type="checkbox"
                  id="delete-roles"
                  v-model="checkPermission"><span>Delete roles</span>
              </div>
            </div>
            <!-- END 1st ROW -->
            <!-- 2ND ROW -->
            <div class="row">
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.view_users"
                  type="checkbox"
                  id="view-users"
                  v-model="checkPermission"><span>View users</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.create_users"
                  type="checkbox"
                  id="create-users"
                  v-model="checkPermission"><span>Create users</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.edit_users"
                  type="checkbox"
                  id="edit-users"
                  v-model="checkPermission"><span>Edit users</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.delete_users"
                  type="checkbox"
                  id="delete-users"
                  v-model="checkPermission"><span>Delete users</span>
              </div>
            </div>
            <!-- END 2ND ROW -->
            <!-- 3RD ROW -->
            <div class="row">
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.view_category"
                  type="checkbox"
                  id="view-category"
                  v-model="checkPermission"><span>View category</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.create_category"
                  type="checkbox"
                  id="create-category"
                  v-model="checkPermission"><span>Create category</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.edit_category"
                  type="checkbox"
                  id="edit-category"
                  v-model="checkPermission"><span>Edit category</span>
              </div>
              <div v-if="!this.nameValue || permissionAccess" class="col-md-3">
                <input :value="adminConfigPermissions.delete_category"
                  type="checkbox"
                  id="delete-category"
                  v-model="checkPermission"><span>Delete category</span>
              </div>
            </div>
            <!-- END 3RD ROW -->
            <!-- 4TH ROW -->
            <div class="row">
              <div class="col-md-3">
                <input :value="usersConfigPermissions.view_expenses"
                  type="checkbox"
                  id="view-expenses"
                  v-model="checkPermission"><span>View expenses</span>
              </div>
              <div class="col-md-3">
                <input :value="usersConfigPermissions.create_expenses"
                  type="checkbox"
                  id="create-expenses"
                  v-model="checkPermission"><span>Create expenses</span>
              </div>
              <div class="col-md-3">
                <input :value="usersConfigPermissions.edit_expenses"
                  type="checkbox"
                  id="edit-expenses"
                  v-model="checkPermission"><span>Edit expenses</span>
              </div>
              <div class="col-md-3">
                <input :value="usersConfigPermissions.delete_expenses"
                  type="checkbox"
                  id="delete-expenses"
                  v-model="checkPermission"><span>Delete expenses</span>
              </div>
            </div>
            <!-- END 4TH ROW -->
            <!-- 5TH ROW -->
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
              <div class="col-md-6">
                <input :value="usersConfigPermissions.edit_password"
                  type="checkbox"
                  id="edit-password"
                  v-model="checkPermission"><span>Edit password</span>
              </div>
            </div>
            <!-- END 5TH ROW -->
            <div v-if="permissionsError"
            class="row">
              <div class="col">
                <span class="error" 
                :aria-errormessage="permissionsError">
                  {{ permissionsError }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group row mb-0">
      <div class="col-md-12">
          <button v-if="disabled !== 'disabled'" 
          type="submit" 
          @click="addRole"
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
          class="block__form--add-role-btn btn btn-primary">Add Role</button>

          <button v-else 
          type="submit"
          disabled
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)"
          class="block__form--add-role-btn btn btn-primary">
            <font-awesome-icon 
            icon="spinner" 
            size="1x" 
            spin fixed-width>
            </font-awesome-icon> Adding...
          </button>
      </div>
    </div>
  </form>
</template>

<script>
  import objMixin from '../../mixins/objMixin';
  import { APIService } from '../../services/APIService';

  const apiService = new APIService();

  export default {
    name: 'components-roles-add-component',
    mixins: [ objMixin ],
    data: function() {
      return {
        nameValue: null,
        permissions: [],
        permissionAccess: false,
        typeError: null,
        permissionsError: null,
        permissionsErrorCustomText: 'The permissions field is required.', 
        disabled: '',
        ariaDisabledReadOnly: false
      }
    },
    props: {
      adminConfigPermissions: {
        type: Object
      },
      usersConfigPermissions: {
        type: Object
      }
    },
    methods: {
      redirect: function(tab) {
        this.$emit('tabChanging', tab);
      },

      addRole: function(e) {
        e.preventDefault();
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;
        
        // If the role is not admin, admin permissions should be disabled
        this.permissions = (!this.permissionAccess) 
          ? _.filter(this.permissions, (obj) => {
            return !_.values(this.adminConfigPermissions).includes(obj)
              && _.values(this.usersConfigPermissions).includes(obj)
          })
          : this.permissions;

        // Check if the permission/s has/have been selected, update the permissions data
        let selectedPermissions = JSON.stringify(this.checkboxValProcess({}, this.permissions, this));
        console.log(this.permissions);

        // Consume an API endpoint with a payload
        let payload = {
          name: this.nameValue,
          permission: selectedPermissions
        }

        // Validation errors
        // Validate empty permissions object
        // Should not accept an empty permission object
        if (selectedPermissions === JSON.stringify({})) {
          this.permissionsError = this.permissionsErrorCustomText;
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        } else {
          apiService.addData(this.$route.name, payload).then((res) => {
            console.log(res.data.message);
            // Validation errors
            this.typeError = null;
            this.permissionsError = null;
            // Reload the list
            console.log(`added ID :: ${res.data.id}`);
            this.$emit('addNewData', this.$store.dispatch('roleNew', res.data.id));
            // Redirect back to list tab
            this.redirect('first-tab');
            this.disabled = '';
            this.ariaDisabledReadOnly = false; 
            
          }).catch((err) => {
            // console.log(Promise.reject(err.response.data.errors.name[0]));
            // Validation errors
            this.typeError = (!this.nameValue || err.response.data.errors.name) ? err.response.data.errors.name[0] : null;
            this.permissionsError = (!this.permissions || err.response.data.errors.permission) 
              ? err.response.data.errors.permission[0] 
              : (JSON.stringify(this.permissions) === JSON.stringify({}))
                ? this.permissionsErrorCustomText
                : null;
            console.log(this.permissions);
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          });
        }
      }
    },

    created() {

    },

    mounted() {
      
    },

    computed: {
      checkRole: {
        get: function() {
          // When the autofocus comes into play, check for current role name to be modified
          console.log(this.nameValue);
          
          this.permissionAccess = this.roleAccess(this.nameValue, 'admin') ? true : false;
          
          console.log(this.permissionAccess);

          console.log(this.permissions);
          return this.nameValue;
          
        },
        set: function(value) {
          this.nameValue = value;
        }
      },

      checkPermission: {
        get: function() {
          this.permissions = _.filter(this.permissions, (obj) => {
            return _.values(this.adminConfigPermissions).includes(obj)
            || _.values(this.usersConfigPermissions).includes(obj)
          });

          console.log('----Check admin permission----');
          console.log(this.permissions);

          // Temporarily store admin permissions if possible for re-selected
          //this.$store.dispatch('tempPermissionsSingle', this.permissions);

          console.log('----Check permission----');
          console.log(this.permissions);
          //console.log(this.$store.getters.tempPermissionsSingle);

          return this.permissions;
        },
        set: function(value) {
          this.permissions = value;
        }
      }
    }
  }
</script>
