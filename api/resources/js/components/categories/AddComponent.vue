<template>
  <form class="block__form">
    <div class="form-group row">
      <label for="type" class="col-md-4 col-form-label text-md-right">Type</label>
      <div class="col-md-6">
          <input id="type" 
          type="text" 
          v-model="type" 
          class="form-control" 
          name="type" 
          required 
          autocomplete="type" 
          autofocus>
          <span v-if="typeError" 
          class="error" 
          :aria-errormessage="typeError">
            {{ typeError }}
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="name" class="col-md-4 col-form-label text-md-right">Description</label>
      <div class="col-md-6">
          <textarea id="description"
          v-model="description" 
          class="form-control" 
          name="description" 
          required 
          autocomplete="description"></textarea>
      </div>
    </div>
    <div class="form-group row mb-0">
      <div class="col-md-6 offset-md-4">
        <button v-if="disabled !== 'disabled'"
        type="submit" 
        @click="addCategory"
        :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
        class="btn btn-primary">Add Category</button>

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
  import { APIService } from '../../services/APIService';
  import fieldAccessMixin from '../../mixins/fieldAccessMixin';

  const apiService = new APIService();

  export default  {
    name: 'components-categories-add-component',
    mixins: [ fieldAccessMixin ],
    data: function() {
      return {
        type: null,
        description: null,
        typeError: null,
        disabled: '',
        ariaDisabledReadOnly: false
      }
    },
    methods: {
      redirect: function($tab) {
        this.$emit('tabChanging', $tab);
      },

      addCategory: function(e) {
        e.preventDefault();
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;

        // Consume an API endpoint with a payload
        let payload = {
          type: this.type,
          description: this.description
        }

        apiService.addData(this.$route.name, payload).then((res) => {
          if (typeof res.data.message.error === 'undefined') {
            console.log(res.data.message);
            // Validation errors
            this.typeError = null;
            // Reload the list
            console.log(`added ID :: ${res.data.id}`);
            this.$emit('addNewData', this.$store.dispatch('categoryNew', res.data.id));
            this.$store.dispatch('categoryList');
            // Redirect back to list tab
            this.redirect('first-tab');
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          } else {
            console.log(res.data.message.error);
            // Validation errors
            this.typeError = (!this.type || res.data.message.error.type) ? res.data.message.error.type[0] : null;
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          }
        }).catch((err) => {
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        });

      }
    },

    mounted() {
      
    }
}


</script>
