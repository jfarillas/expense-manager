<template>
  <div class="container block">
    <div class="row justify-content-center">
      <div class="col-md-12 block__div">
        <tabs ref="tabs" :options="{ useUrlFragment: false }">
          <tab v-if="$can('view_roles')"
          id="first-tab" 
          name="Roles"
          class="block__div--roles table-responsive">
            <List :fetched-data="data"
            :added-id="id"
            :editData="editData"
            :updated-id="updatedID"
            :permissions="permissions"
            :admin-config-permissions="adminConfigPermissions"
            :users-config-permissions="usersConfigPermissions"
            :highlight-row-success-added="highlightRowSuccessAdded"
            @updatedData="updateDataList"
            @deleteId="deleteDataList" />
          </tab>
          <tab v-if="$can('create_roles')"
          id="second-tab" 
          name="Add new roles">
            <Add :admin-config-permissions="adminConfigPermissions"
            :users-config-permissions="usersConfigPermissions"
            @tabChanging="tabChanged" 
            @addNewData="addNewDataList" />
          </tab>
        </tabs>
      </div>
    </div>
  </div>
</template>

<script>
  import { APIService } from '../../services/APIService';
  import List from '../../components/roles/ListComponent';
  import Add from '../../components/roles/AddComponent';
  import objMixin from '../../mixins/objMixin';

  const apiService = new APIService();

  export default {
    name: 'components-roles-tabs-component',
    mixins: [ objMixin ],
    components: { 
      List,
      Add
    },

    data () {
      return {
        id: null,
        data: null,
        editData: [],
        updatedID: null,
        permissions: [],
        adminConfigPermissions: null,
        usersConfigPermissions: null,
        highlightRowSuccessAdded: false // Row highlighting for added data
      }
    },
    
    methods: {
      formatData: function(data) {
        // Formatting values from the data in preparing items for the possible modification/deletion
        //_.keys(data).map((key, index) => {
          this.editData[data.id] = {
            user_id: data.user_id,
            name: data.name
          };

          // Append permission/s to the permissions data
          this.$set(this.permissions, data.id, []);
          
          _.keys(data.get_permissions).forEach(index => {
            _(this.permissions[data.id])
            .push(data.get_permissions[index].name)
            .value();
          });
        //});
        console.log(this.editData);
        console.log(this.permissions);
      },

      fetchAll: function(id) {
        let roleData = [];
        this.$store.dispatch('rolesList').then(res => {
          Object.keys(res).forEach((key) => {
            Object.keys(res[key].user_roles).forEach((subKey) => {
              res[key].user_roles[subKey]['user_id'] = res[key].user_id;
            });
            roleData.push(res[key].user_roles);
          });
          this.data = roleData;
          console.log(this.data);
          Object.keys(this.data).forEach((k) => {
            // Formatting values from the data in preparing items for the possible modification/deletion
            this.formatData(this.data[k][0]);
          });
          
        });
      },

      addNewDataList: function(obj) {
        obj.then(res => {
          // Get new inserted ID from the data
          this.id = res[0].id;
          // Unshift new data if the data array is not empty. Otherwise use push()
          if (this.data.length === 0) {
            this.data = [];
            _(this.data).push(res[0]).value();
          } else {
            _(this.data).unshift(res).value();
          }
          console.log(res);
          Object.keys(this.data).forEach((k) => {
            // Formatting values from the data in preparing items for the possible modification/deletion
            this.formatData(this.data[k][0]);
          });
          // Row hightlight fade in
          this.highlightRowSuccessAdded = true;
          // Row hightlight fade out
          setTimeout(() => this.highlightRowSuccessAdded = false, 2000);
        });
      },

      updateDataList: function(value) {
        //let updatedIndex = _.findIndex(this.data, function(obj) { return obj.id === value.id });

        // Indicates that a specific record has been updated successfully
        this.updatedID = value.id;

        value.obj.then(res => {
          let convertRoleList = {
            id: res[0].id,
            user_id: value.user_id,
            name: res[0].name,
            get_permissions: res[0].get_permissions
          }
          console.log(this.observerToNormalObj(convertRoleList));
          //this.data.splice(updatedIndex, 1, this.observerToNormalObj(convertRoleList));
          console.log(this.data);
          // Flush the data keys when it reloads (to prevent from duplicates)
          // before it formats its value
          this.editData = [];
          Object.keys(this.data).forEach((k) => {
            console.log(`${this.data[k][0].id} === ${value.id}`);
            if (this.data[k][0].id === value.id) {
              this.data[k].splice(0, 1, this.observerToNormalObj(convertRoleList));
            }
            // Formatting values from the data in preparing items for the possible modification/deletion
            this.formatData(this.data[k][0]);
          });
        });
      },

      deleteDataList: function(id) {
        this.data = _.remove(this.data, function(obj) { return obj.id !== id });
        // Flush the data keys when it reloads (to prevent from duplicates)
        // before it formats its value
        this.editData = [];
        this.formatData(this.data);
      },

      tabChanged: function(value) {
        this.$refs.tabs.selectTab(`#${value}`);
        //console.log(this.$refs.tabs.selectTab(value));
        console.log('Tab changed to: '+value);
      }
    },

    created() {
      this.adminConfigPermissions = JSON.parse(`${process.env.MIX_ADMIN_PERMISSIONS}`);
      this.usersConfigPermissions = JSON.parse(`${process.env.MIX_USERS_PERMISSIONS}`);
      this.fetchAll(this.id);
    },

    mounted() {
      console.log('Component mounted.');
      console.log(this.$route);
      console.log(this.adminConfigPermissions);
    }
  }
</script>
