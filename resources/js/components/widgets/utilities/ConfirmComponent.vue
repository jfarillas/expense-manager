<template>
  <b-modal :id="`modal-center-${id}`" 
  centered 
  :title="title" 
  :okVariant="btnStatus" 
  :okTitle="btnYesText"
  :cancelTitle="btnCancelText"
  @ok="primaryAction(id)">
    <slot :name="`confirm-delete-${id}`"></slot>
  </b-modal>
</template>

<script>

  export default  {
    name: 'components-utilities-confirm-component',
    props: {
      id: {
        type: Number
      },
      title: {
        type: String
      },
      btnYesText: {
        type: String
      },
      btnCancelText: {
        type: String
      },
      btnStatus: {
        type: String
      },
      action: {
        type: String
      }
    },
    mounted () {

    },
    data () {
      return {

      }
    },
    methods: {
      primaryAction: function(id) {
        (this.action === 'delete') ? this.$emit('delete', id) : '';
      }
    },
    computed: {

    }
}


</script>
