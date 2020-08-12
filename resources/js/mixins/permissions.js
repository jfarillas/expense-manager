/**
 * Customize Permission setup
 * authored by: Joseph Ian Farillas
 */
export default {
  methods: {
    $can(permissionName) {
      return Permissions.indexOf(permissionName) !== -1;
    },

    verifyPermission: (collection, prop, value) => {
      return typeof _.find(collection, (o) => { 
        return prop in o && o.name === value 
      }) !== 'undefined' ? true : false;
    },

    setPermission: (collection, prop, value) => {
      return typeof _.find(collection, (o) => { 
        return prop in o && o.name === value 
      }) !== 'undefined' ? _.find(collection, (o) => { 
        return prop in o && o.name === value 
      }).name 
      : _(collection).push({ [prop]: value }).value();
    },

    roleAccess: (str, role) => { 
      const regex = RegExp(role, 'i');
      return regex.test(str);
    }
  },
};