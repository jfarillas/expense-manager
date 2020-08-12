<template>
  <div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
    <div class="card">
      <div class="card-header">Dashboard</div>

      <div class="card-body">
        <Chart
        v-if="loaded"
        :chartdata="chartdata"
        :options="options" />
      </div>
    </div>
    </div>
  </div>
  </div>
</template>

<script>
  import Chart from '../components/widgets/charts/Chart'

  export default {
  name: 'Dashboard',
  components: { Chart },

  data () {
    return {
      id: null,
      loaded: false,
      chartdata: null,
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    }
  },

  methods: {
    loadGraph: function(id) {
    this.loaded = false;
    let labels = [];
    let data = [];
    this.$store.dispatch('expensesPerCategory').then(res => {
      _.keys(res).forEach(index => {
        // Populate category types as labels
        _(labels)
        .push(res[index].type)
        .value();
        // Total amount per category
        _(data)
        .push(res[index].total_amount)
        .value();
      });
      console.log(labels);
      this.chartdata = {
        labels: labels,
        datasets: [
          {
            label: 'Total expenses per category',
            backgroundColor: '#f99d17',
            data: data
          }
        ]
      };
      this.loaded = true;
    })
    }
  },

  created() {
    this.loadGraph(this.id);
  },

  mounted() {
    console.log('Component mounted.')
  }
  }
</script>
