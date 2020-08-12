<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            {{ appDesc }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        appDesc: null
      }
    },
    created() {
      this.appDesc = `${process.env.MIX_DESC}`;
    },
    mounted() {
      console.log('Component mounted.')
    }
  }
</script>
