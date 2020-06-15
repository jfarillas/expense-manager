$(document).ready(function(){
  $('#logout-button').on('click', function(e) {
    e.preventDefault();
    console.log('Logging out...');
    $('#logout-form').submit();
  });

  // Get roles
  let reverseExistingArr = (arr) => {
    for (var i = 0; i <= Math.floor((arr.length - 1) / 2); i++) {
        let el = arr[i];
        arr[i] = arr[arr.length - 1 - i];
        arr[arr.length - 1 - i] = el;
    }
    return arr;
  }

  $('#register-role').select2({
    ajax: {
      url: '/api/v1/guest-view-roles',
      processResults: function (res) {
        let results = [];
        Object.keys(res).forEach(index => {
          results.push({
            id: res[index].name,
            text: res[index].name
          });
        });

        reverseExistingArr(results);

        return { results }
      }
    },
    placeholder: 'Select a role'
  });
});