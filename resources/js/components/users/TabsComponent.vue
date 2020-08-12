<template>
  <div class="container block">
    <div class="row justify-content-center">
      <div class="col-md-12 block__div">
        <tabs ref="tabs" :options="{ useUrlFragment: false }">
          <tab v-if="$is('admin') && $can('view_users')"
          id="first-tab" 
          name="Users" 
          class="block__div--users table-responsive">
            <List :fetched-data="data"
            :editData="editData"
            :updated-id="updatedID"
            @updatedData="updateDataList"
            @deleteId="deleteDataList" />
          </tab>
          <!-- TODO: Add edit password form for non-admin users -->
          <tab v-if="$can('edit_password')"
          id="second-tab" 
          name="Edit Password">
            <EditPassword />
          </tab>
        </tabs>
      </div>
    </div>
  </div>
</template>

<script>
  import List from '../../components/users/ListComponent';
  import EditPassword from '../../components/users/EditPasswordComponent';

  export default  {
    name: 'components-users-tabs-component',
    components: { 
      List,
      EditPassword 
    },

    data () {
      return {
        id: null,
        data: null,
        editData: [],
        isTabChange: false,
        updatedID: null
      }
    },
    
    methods: {
      formatData: function(data) {
        // Formatting values from the data in preparing items for the possible modification/deletion
        _.keys(data).map((key, index) => {
          this.editData[data[key].id] = {
            name: data[key].name,
            email: data[key].email,
            password: '**********',
            role: data[key].roles[0].name
          }
        });
        console.log(this.editData);
      },

      fetchAll: function(id) {
        this.$store.dispatch('users').then(res => {
          this.data = res;
          console.log(this.data);
          // Formatting values from the data in preparing items for the possible modification/deletion
          this.formatData(this.data);
        });
      },

      updateDataList: function(value) {
        let updatedIndex = _.findIndex(this.data, function(obj) { return obj.id === value.id });

        // Indicates that a specific record has been updated successfully
        this.updatedID = value.id;

        value.obj.then(res => {
          this.data.splice(updatedIndex, 1, res[0]);
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
        this.isTabChange = true;
        console.log('Tab changed to: '+value);
      }
    },

    created() {
      this.fetchAll(this.id);
    },

    mounted() {
      console.log('Component mounted.');
      console.log(this.$route);
    }
}


</script>
