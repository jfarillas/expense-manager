<template>
  <transition-group name="list" tag="tbody">
    <tr v-for="value in fetchedData" :key="value[0].id" 
    :class="{
      'list-enter-active': highlightRowSuccessAdded,
      'highlight-success-updated': (value[0].id === dataId && highlightRow),
      'highlight-error': (value[0].id === dataId && highlightRowError)
    }"
    class="list-item">
      <slot :row="value[0]" :index="value[0].id"></slot>
    </tr>
  </transition-group>
</template>

<script>

  export default  {
    name: 'components-roles-edit-component',
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
