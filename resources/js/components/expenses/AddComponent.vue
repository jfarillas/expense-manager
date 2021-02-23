<template>
  <form class="block__form">
    <div class="form-group row">
      <label for="expense_date" class="col-md-4 col-form-label text-md-right">Date</label>
      <div class="col-md-6">
        <datetime v-model="expense_date"
        type="datetime"
        input-id="expense_date"
        use12-hour
        input-class="form-control"></datetime>
        <span v-if="datetimeError" 
        class="error" 
        :aria-errormessage="datetimeError">
          {{ datetimeError }}
        </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="category" class="col-md-4 col-form-label text-md-right">Category</label>
      <div class="col-md-6">
        <Select2 v-model="categoryValue" 
        :options="categoryOptions"
        :settings="{ placeholder: 'Select a category' }"
        :id="categoryValue"
        @change="myChangeEvent($event)" 
        @select="mySelectEvent($event)"
        required />
        <span v-if="categoryIDError" 
        class="error" 
        :aria-errormessage="categoryIDError">
          {{ categoryIDError }}
        </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="account" class="col-md-4 col-form-label text-md-right">Type</label>
      <div class="col-md-6">
        <Select2 v-model="accountValue" 
        :options="accountOptions"
        :settings="{ placeholder: 'Select an expense type' }"
        id="accountValue"
        @change="myChangeEvent($event)" 
        @select="mySelectEvent($event)"
        required />
        <span v-if="accountError" 
        class="error" 
        :aria-errormessage="accountError">
          {{ accountError }}
        </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="amount" class="col-md-4 col-form-label text-md-right">Amount</label>
      <div class="col-md-6">
        <input v-model="amount"
        type="number"
        id="amount"
        step="0.01"
        autocomplete="amount"
        class="form-control" />
        <span v-if="amountError" 
        class="error" 
        :aria-errormessage="amountError">
          {{ amountError }}
        </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
      <div class="col-md-6">
          <textarea id="description"
          v-model="description" 
          class="form-control" 
          name="description"
          autocomplete="description"></textarea>
      </div>
    </div>
    <div class="form-group row mb-0">
      <div class="col-md-6 offset-md-4">
          <button v-if="disabled !== 'disabled'" 
          type="submit" 
          @click="addExpense"
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)"
          class="btn btn-primary">Add Expense</button>

          <button v-else 
          type="submit"
          disabled
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)"
          class="btn btn-primary">
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
  import dateTimeMixin from '../../mixins/dateTimeMixin';
  import fieldAccessMixin from '../../mixins/fieldAccessMixin';
  import { APIService } from '../../services/APIService';

  const apiService = new APIService();

  export default  {
    name: 'components-expenses-add-component',
    mixins: [ 
      dateTimeMixin,
      fieldAccessMixin
    ],
    data: function() {
      return {
        id: null,
        data: null,
        expense_date: null,
        categoryValue: null, // For selecting a category
        categoryOptions: null,
        accountValue: null, // For selecting an expense type
        accountOptions: null,
        amount: null,
        description: null,
        datetimeError: null,
        categoryIDError: null,
        accountError: null,
        amountError: null,
        disabled: '',
        ariaDisabledReadOnly: false
      }
    },

    created () {
      console.log(this.$store.getters.categories);
      this.$store.dispatch('categories').then(res => {
        this.categoryOptions = res;
      });
      
      console.log(this.categoryOptions);
      // Account options
      this.accountOptions = [ 'cash', 'card', 'savings' ];
      this.amount = '0.00';
    },

    mounted () {

    },
    
    methods: {
      redirect: function($tab) {
        this.$emit('tabChanging', $tab);
      },

      myChangeEvent(val){
        console.log(val);
      },
      
      mySelectEvent({id, text}){
        console.log({id, text})
      },

      addExpense: function(e) {
        e.preventDefault();
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;
        
        (this.expense_date) 
          ? this.expense_date = this.convertDateTime(this.expense_date)
          : '';

        // Consume an API endpoint with a payload
        let payload = {
          expense_date: this.expense_date,
          categories_id: this.categoryValue,
          type: this.accountValue,
          amount: this.amount,
          description: this.description
        }

        apiService.addData(this.$route.name, payload).then((res) => {
          if (typeof res.data.message.error === 'undefined') {
            console.log(res.data.message);
            // Validation errors
            this.datetimeError = null;
            this.categoryIDError = null;
            this.accountError = null;
            this.amountError = null;
            // Reload the list
            console.log(`added ID :: ${res.data.id}`);
            this.$emit('addNewData', this.$store.dispatch('expenseNew', res.data.id));
            this.$store.dispatch('expenses');
            // Redirect back to list tab
            this.redirect('first-tab');
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          } else {
            console.log(res.data.message.error);
            // Validation errors
            this.datetimeError = (!this.expense_date || res.data.message.error.expense_date) ? res.data.message.error.expense_date[0] : null;
            this.categoryIDError = (!this.categoryValue || res.data.message.error.categories_id) ? res.data.message.error.categories_id[0] : null;
            this.accountError = (!this.accountValue || res.data.message.error.type) ? res.data.message.error.type[0] : null;
            this.amountError = (!this.amount || res.data.message.error.amount) ? res.data.message.error.amount[0] : null;
            // Convert back to default ISO datetime
            (this.expense_date) 
            ? this.expense_date = new Date(this.expense_date).toISOString()
            : '';

            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          }
          
        }).catch((err) => {
          // Convert back to default ISO datetime
          (this.expense_date) 
          ? this.expense_date = new Date(this.expense_date).toISOString()
          : '';
          
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        });

      }
    },
    computed: {

    }
}


</script>
