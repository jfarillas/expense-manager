<template>
  <transition-group name="list" tag="tbody">
    <tr v-for="value in fetchedData" :key="value.id" 
    :class="{
      'list-enter-active': (value.id === addedId && highlightRowSuccessAdded),
      'highlight-success-updated': (value.id === dataId && highlightRow),
      'highlight-error': (value.id === dataId && highlightRowError)
    }"
    class="list-item">
      <slot :row="value" :index="value.id"></slot>
    </tr>
  </transition-group>
</template>

<script>

  export default  {
    name: 'components-categories-edit-component',
    props: {
      fetchedData: {
        type: Array
      },
      addedId: {
        type: Number
      },
      dataId: {
        type: Number
      },
      highlightRowSuccessAdded: {
        type: Boolean
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
      
    },
    computed: {

    }
}


</script>
