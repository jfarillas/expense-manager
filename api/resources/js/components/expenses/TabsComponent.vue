<template>
  <div class="container block">
    <div class="row justify-content-center">
      <div class="col-md-12 block__div">
        <tabs ref="tabs" :options="{ useUrlFragment: false }">
          <tab v-if="$can('view_expenses')"
          id="first-tab" 
          name="Expenses" 
          class="block__div--expenses table-responsive">
            <List :fetched-data="data"
            :added-id="id" 
            :editData="editData"
            :updated-id="updatedID"
            :highlight-row-success-added="highlightRowSuccessAdded"
            @updatedData="updateDataList"
            @deleteId="deleteDataList" />
          </tab>
          <tab v-if="$can('create_expenses')" 
          id="second-tab" 
          name="Add new expenses" 
          class="block__div--expense">
            <Add @tabChanging="tabChanged" @addNewData="addNewDataList" />
          </tab>
        </tabs>
      </div>
    </div>
  </div>
</template>

<script>
  import List from '../../components/expenses/ListComponent';
  import Add from '../../components/expenses/AddComponent';

  export default  {
    name: 'components-expenses-tabs-component',
    components: { 
      List,
      Add
    },

    data () {
      return {
        id: null,
        data: null,
        editData: [],
        isTabChange: false,
        updatedID: null,
        highlightRowSuccessAdded: false, // Row highlighting for added data
      }
    },

    methods: {
      formatData: function(data) {
        // Formatting values from the data in preparing items for the possible modification/deletion
        _.keys(data).map((key, index) => {
          this.editData[data[key].id] = {
            expense_date: new Date(data[key].expense_date).toISOString(),
            categories_id: data[key].categories_id,
            account: data[key].account,
            amount: data[key].amount,
            description: data[key].description
          }
        });
        console.log(this.editData);
      },

      fetchAll: function(id) {
        this.$store.dispatch('expenses').then(res => {
          this.data = res;
          console.log(this.data);
          // Formatting values from the data in preparing items for the possible modification/deletion
          this.formatData(this.data);
        });
      },

      addNewDataList: function(obj) {
        obj.then(res => {
          // Get new inserted ID from the data
          this.id = res[0].id;
          let wrapper = _(this.data)
          .unshift(res[0])
          .value();
          console.log(this.data);
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
