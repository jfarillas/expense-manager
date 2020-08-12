<template>
  <section>
    <span slot="edit" @click="editItem" class="edit">
      <font-awesome-icon 
      icon="edit" 
      size="1x"
      title="Edit">
      </font-awesome-icon>
    </span>
    <span slot="delete" v-b-modal="'modal-center-'+valueId" class="delete">
      <font-awesome-icon
      icon="trash-alt" 
      size="1x"
      title="Delete">
      </font-awesome-icon>
      <Confirm :id="valueId" 
      :title="confirmTitle"
      :btn-yes-text="'YES'"
      :btn-cancel-text="'NO'"
      :btn-status="'danger'"
      :action="'delete'"
      @delete="deleteItem">
        <p :slot="`confirm-delete-${valueId}`" class="my-4">
          {{ confirmBody }} <strong>{{ confirmBodyItemNo }}</strong>?
        </p>
      </Confirm>
    </span>
  </section>
</template>

<script>
  import Confirm from './ConfirmComponent';

  export default  {
    name: 'components-utilities-actions-components',
    props: {
      valueId: {
        type: Number
      },
      confirmTitle: {
        type: String
      },
      confirmBody: {
        type: String
      },
      confirmBodyItemNo: {
        type: String
      }
    },
    components: {
      Confirm
    },
    mounted () {

    },
    data () {
      return {

      }
    },
    methods: {
      editItem: function() {
        this.$emit('edit', true);
      },

      deleteItem: function(value) {
        this.$emit('delete', value);
      }
    },
    computed: {

    }
}


</script>
