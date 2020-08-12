<template>
  <table class="table table-dark">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Category</th>
        <th scope="col">Account</th>
        <th scope="col">Amount</th>
        <th scope="col">Description</th>
        <th v-if="$can('edit_expenses') || $can('delete_expenses')"
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
        <td>{{ value.expense_date | moment("MMMM DD, YYYY, hh:mm A") }}</td>
        <td>{{ value.categories.type }}</td>
        <td>{{ value.account }}</td>
        <td>{{ value.amount }}</td>
        <td>{{ value.description | ellipsis }}</td>
        <td v-if="$can('edit_expenses') || $can('delete_expenses')"
        class="d-flex flex-row justify-content-center">
          <actions @edit="editItem(value.id)" 
          @delete="deleteExpense"
          :confirm-title="'Delete Expense'"
          :confirm-body="'Permanently delete this expense'"
          :confirm-body-item-no="`#${value.id}`"
          :value-id="value.id">
            <slot v-if="$can('edit_expenses')" name="edit"></slot>
            <slot v-if="$can('delete_expenses')" name="delete"></slot>
          </actions>
        </td>
      </tr>
    </transition-group>
    <edit-expense v-if="fetchedData && editFlag" 
    :fetched-data="fetchedData"
    :added-id="addedId"
    :data-id="editID"
    :highlight-row-success-added="highlightRowSuccessAdded"
    :highlight-row="highlightRowSuccessUpdated"
    :highlight-row-error="highlightRowError">
      <template slot-scope="{ row, index }">
        <th scope="row">{{ row.id }}</th>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <datetime v-model="editData[index].expense_date"
          type="datetime"
          :input-id="`expense_date-${row.id}`"
          use12-hour
          input-class="form-control"></datetime>
          <div v-if="editID === errorID && datetimeError"
          class="container-error"
          :aria-errormessage="datetimeError">
            <span class="error">
              {{ datetimeError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.expense_date | moment("MMMM DD, YYYY, hh:mm A") }}
        </td>
        
        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <Select2 v-model="editData[index].categories_id"
          :options="categorySelect"
          :settings="{ placeholder: 'Select a category' }"
          :id="`category-${row.id}`"
          @change="myChangeEvent($event)" 
          @select="mySelectEvent($event)"
          required />
          <div v-if="editID === errorID && categoryIDError"
          class="container-error"
          :aria-errormessage="categoryIDError">
            <span class="error">
              {{ categoryIDError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.categories.type }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <Select2 v-model="editData[index].account"
          :options="accountOptions"
          :settings="{ placeholder: 'Select an expense type' }"
          :id="`account-${row.id}`"
          @change="myChangeEvent($event)" 
          @select="mySelectEvent($event)"
          required />
          <div v-if="editID === errorID && accountError"
          class="container-error"
          :aria-errormessage="accountError">
            <span class="error">
              {{ accountError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.account }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <input v-model="editData[index].amount"
          type="number"
          :id="`amount-${row.id}`"
          step="0.01"
          :autocomplete="`amount-${row.id}`"
          class="form-control" />
          <div v-if="editID === errorID && amountError"
          class="container-error"
          :aria-errormessage="amountError">
            <span class="error">
              {{ amountError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.amount }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <textarea v-model="editData[index].description"
          :id="`description-${row.id}`"
          class="form-control" 
          :name="`description-${row.id}`"
          :autocomplete="`description-${row.id}`"></textarea>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.description | ellipsis }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)" 
        class="d-flex flex-row justify-content-center">
          <button v-if="disabled !== 'disabled'"
          @click="updateExpense(editData[index], row.id)"
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
          @delete="deleteExpense"
          :confirm-title="'Delete Expense'"
          :confirm-body="'Permanently delete this expense'"
          :confirm-body-item-no="`#${row.id}`"
          :value-id="row.id">
            <slot name="edit"></slot>
            <slot name="delete"></slot>
          </actions>
        </td>
      </template>
    </edit-expense>
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
</template>
<script>
  import { APIService } from '../../services/APIService';
  import dateTimeMixin from '../../mixins/dateTimeMixin';
  import fieldAccessMixin from '../../mixins/fieldAccessMixin';

  const apiService = new APIService();

  export default  {
    name: 'components-expenses-list-component',
    mixins: [ 
      dateTimeMixin,
      fieldAccessMixin 
    ],
    data: function() {
      return {
        editFlag: false, // List of data subject for modification
        editID: null, // Current data ID to modify
        categoryOptions: null,
        accountOptions: null,
        editUpdatedID: false, // Lock/unlock updated ID for editing
        errorID: null, // Current data ID that has validation error
        datetimeError: null,
        categoryIDError: null,
        accountError: null,
        amountError: null,
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
      highlightRowSuccessAdded: {
        type: Boolean
      }
    },
    created () {
      // this.categoryOptions = this.$store.getters.categories;
      this.accountOptions = [ 'cash', 'card', 'savings' ];

    },
    mounted () {
      console.log(`editUpdatedID to ${this.editUpdatedID} for edit state.`);
    },
    methods: {
      editItem: function(value) {
        console.log(`Edit item :: ${value}`);
        this.editFlag = true;
        this.editID = value;
        // Clear the updated ID flag in unlocking each edit field
        (this.updatedId) ? this.unlockUpdatedID : '';
      },

      updateExpense: function(modifiedData, id) {
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;
        console.log(modifiedData);
        modifiedData.expense_date = this.convertDateTime(modifiedData.expense_date);

        // Consume an API endpoint with a payload
        let payload = {
          expense_date: modifiedData.expense_date,
          categories_id: modifiedData.categories_id,
          account: modifiedData.account,
          amount: modifiedData.amount,
          description: modifiedData.description
        }

        apiService.updateData(this.$route.name, payload, id).then((res) => {
          if (typeof res.data.message.error === 'undefined') {
            console.log(res.data.message);
            // Validation errors
            this.datetimeError = null;
            this.categoryIDError = null;
            this.accountError = null;
            this.amountError = null;
            // Reload the list
            console.log(`Updated ID :: ${id}`);
            this.$emit('updatedData', { id: id, obj: this.$store.dispatch('expenseUpdate', id) });
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
            this.datetimeError = (!modifiedData.expense_date || res.data.message.error.expense_date) ? res.data.message.error.expense_date[0] : null;
            this.categoryIDError = (!modifiedData.categories_id || res.data.message.error.categories_id) ? res.data.message.error.categories_id[0] : null;
            this.accountError = (!modifiedData.account || res.data.message.error.account) ? res.data.message.error.account[0] : null;
            this.amountError = (!modifiedData.amount || res.data.message.error.amount) ? res.data.message.error.amount[0] : null;
            // Convert back to default ISO datetime
            modifiedData.expense_date = new Date(modifiedData.expense_date).toISOString();
            // Row highlighting for updating data with errors
            this.highlightRowError = true;
            setTimeout(() => this.highlightRowError = false, 1000);
            this.errorID = id;
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          }
        }).catch((err) => {
          // Convert back to default ISO datetime
          modifiedData.expense_date = new Date(modifiedData.expense_date).toISOString();
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        });

      },

      deleteExpense: function(id) {
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

    computed: {
      unlockUpdatedID: function() {
        this.editUpdatedID = true;
        console.log(`In computed :: editUpdatedID to ${this.editUpdatedID} for edit state.`);
        return this.editUpdatedID;
      },

      categorySelect: function() {
        console.log('In computed category select ::');
        console.log(this.$store.getters.categories);
        return this.categoryOptions = this.$store.getters.categories;
      }
    }
}


</script>
