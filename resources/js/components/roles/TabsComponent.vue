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
        _.keys(data).map((key, index) => {
          this.editData[data[key].id] = {
            name: data[key].name
          };

          // Append permission/s to the permissions data
          this.$set(this.permissions, data[key].id, []);
          
          _.keys(data[key].get_permissions).forEach(index => {
            _(this.permissions[data[key].id])
            .push(data[key].get_permissions[index].name)
            .value();
          });
        });
        console.log(this.editData);
        console.log(this.permissions);
      },

      fetchAll: function(id) {
        this.$store.dispatch('rolesList').then(res => {
          this.data = res;
          console.log(this.data);
          // Formatting values from the data in preparing items for the possible modification/deletion
          this.formatData(this.data);
          // May use this for appending counters on data
          //this.ctr = this.counter(this.data);
        });
      },

      addNewDataList: function(obj) {
        obj.then(res => {
          // Get new inserted ID from the data
          this.id = res[0].id;
          let wrapper = _(this.data)
          .unshift(res[0])
          .value();
          console.log(res);
          // Formatting values from the data in preparing items for the possible modification/deletion
          this.formatData(res);
          // Row hightlight fade in
          this.highlightRowSuccessAdded = true;
          // Row hightlight fade out
          setTimeout(() => this.highlightRowSuccessAdded = false, 2000);
        });
      },

      updateDataList: function(value) {
        let updatedIndex = _.findIndex(this.data, function(obj) { return obj.id === value.id });

        // Indicates that a specific record has been updated successfully
        this.updatedID = value.id;

        value.obj.then(res => {
          let convertRoleList = {
            id: res[0].id,
            name: res[0].name,
            get_permissions: res[0].get_permissions
          }
          console.log(this.observerToNormalObj(convertRoleList));
          this.data.splice(updatedIndex, 1, this.observerToNormalObj(convertRoleList));
          console.log(this.data);
          // Flush the data keys when it reloads (to prevent from duplicates)
          // before it formats its value
          this.editData = [];
          // Formatting values from the data in preparing items for the possible modification/deletion
          this.formatData(this.data);
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
    }
  }
</script>
