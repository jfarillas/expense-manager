<template>
  <form class="block__form">
    <div class="form-group row">
      <label for="type" class="col-md-4 col-form-label text-md-right">New Password</label>
      <div class="col-md-6">
          <input id="password" 
          :type="type" 
          v-model="password" 
          class="form-control" 
          name="password" 
          required 
          autocomplete="password" 
          autofocus>
          <span class="password--icon d-flex flex-row-reverse pr-2">
            <font-awesome-icon
              class="password--icon-eye"
              :icon="icon"
              @click="togglePassword(type, icon)"
              fixed-width>
            </font-awesome-icon>
          </span>
          <span v-if="passwordError" 
          class="error" 
          :aria-errormessage="passwordError">
            {{ passwordError }}
          </span>
      </div>
    </div>
    <div class="form-group row mb-0">
      <div class="col-md-6 offset-md-4">
        <button v-if="disabled !== 'disabled'"
        type="submit" 
        @click="editPassword"
        :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
        class="btn btn-primary">Edit Password</button>

        <button v-else
        type="submit"
        disabled
        :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
        class="btn btn-primary">
          <font-awesome-icon 
          icon="spinner" 
          size="1x" 
          spin fixed-width>
          </font-awesome-icon> Updating...
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import { APIService } from '../../services/APIService';

  const apiService = new APIService();

  export default  {
    name: 'components-edit-password-component',
    data: function() {
      return {
        password: null,
        passwordError: null,
        disabled: '',
        ariaDisabledReadOnly: false,
        type: 'password',
        icon: 'eye'
      }
    },
    props: {
      fetchedData: {
        type: Array
      },
      dataId: {
        type: Number
      },
      highlightRow: {
        type: Boolean
      },
      highlightRowError: {
        type: Boolean
      }
    },
    created () {

    },
    mounted () {
      
    },
    methods: {
      editPassword: function(e) {
        e.preventDefault();
        console.log(this.$id());
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;

        // Consume an API endpoint with a payload
        let payload = {
          password: this.password
        }

        apiService.updatePassword(this.$route.name, payload, this.$id()).then((res) => {
          if (typeof res.data.message.error === 'undefined') {
            console.log(res.data.message);
            // Validation errors
            this.passwordError = null;
            // Reload the list
            console.log(`Updated ID :: ${this.$id()}`);
            this.$emit('updatedData', { id: id, obj: this.$store.dispatch('userUpdate', this.$id()) });
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          } else {
            console.log(res.data.message.error);
            // Validation errors
            this.passwordError = (!this.password || res.data.message.error.password) ? res.data.message.error.password[0] : null;
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          }
        }).catch((err) => {
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        });

      },

      togglePassword: function(type, icon) {
        this.type = type === 'password' ? 'text' : 'password';
        this.icon = icon === 'eye-slash' ? 'eye' : 'eye-slash';
      },
    },
    computed: {

    }
}


</script>
