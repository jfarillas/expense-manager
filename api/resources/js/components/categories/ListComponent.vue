<template>
  <table class="table table-dark">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Type</th>
        <th scope="col">Description</th>
        <th v-if="$can('edit_category') || $can('delete_category')" 
        scope="col" 
        class="text-center">Actions</th>
      </tr>
    </thead>
    <transition-group name="list" tag="tbody" v-if="fetchedData && !editFlag">
      <tr v-for="value in fetchedData" 
      :key="value.id"
      :class="{ 'list-enter-active': (highlightRowSuccessAdded && value.id === addedId) }" 
      class="list-item">
        <th scope="row">{{ value.id }}</th>
        <td>{{ value.type }}</td>
        <td>{{ value.description | ellipsis }}</td>
        <td v-if="$can('edit_category') || $can('delete_category')" 
        class="d-flex flex-row justify-content-center">
          <actions @edit="editItem(value.id)"
          @delete="deleteCategory"
          :confirm-title="'Delete Category'"
          :confirm-body="'Permanently delete this category'"
          :confirm-body-item-no="`#${value.id}`"
          :value-id="value.id">
            <slot v-if="$can('edit_category')" name="edit"></slot>
            <slot v-if="$can('delete_category')" name="delete"></slot>
          </actions>
        </td>
      </tr>
    </transition-group>
    <edit-category v-if="fetchedData && editFlag" 
    :fetched-data="fetchedData"
    :added-id="addedId"
    :data-id="editID"
    :highlight-row-success-added="highlightRowSuccessAdded"
    :highlight-row="highlightRowSuccessUpdated"
    :highlight-row-error="highlightRowError">
      <template slot-scope="{ row, index }">
        <th scope="row">{{ row.id }}</th>
        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <input :id="`type-${row.id}`" 
          type="text" 
          v-model="editData[index].type" 
          class="form-control" 
          :name="`type-${row.id}`" 
          required 
          :autocomplete="`type-${row.id}`"
          autofocus>
          <div v-if="editID === errorID && typeError"
          class="container-error"
          :aria-errormessage="typeError">
            <span class="error">
              {{ typeError }}
            </span>
          </div>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.type }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)">
          <textarea v-model="editData[index].description"
          :id="`description-${row.id}`"
          class="form-control" 
          :name="`description-${row.id}`"
          :autocomplete="`description-${row.id}`"></textarea>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)">
          {{ row.description | ellipsis }}
        </td>

        <td v-if="editID === row.id && (!updatedId || editUpdatedID)" 
        class="d-flex flex-row justify-content-center">
          <button v-if="disabled !== 'disabled'"
          @click="updateCategory(editData[index], row.id)"
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)" 
          class="btn btn-primary">Update</button>

          <button v-else
          disabled
          :aria-disabled="ariaNotEditable(!ariaDisabledReadOnly)"
          class="btn btn-primary">
            <font-awesome-icon 
            icon="spinner" 
            size="1x"
            spin fixed-width>
            </font-awesome-icon> Updating...
          </button>
        </td>

        <td v-if="editID !== row.id || (updatedId === row.id && !editUpdatedID)" 
        class="d-flex flex-row justify-content-center">
          <actions @edit="editItem(row.id)" 
          @delete="deleteCategory"
          :confirm-title="'Delete Category'"
          :confirm-body="'Permanently delete this category'"
          :confirm-body-item-no="`#${row.id}`"
          :value-id="row.id">
            <slot name="edit"></slot>
            <slot name="delete"></slot>
          </actions>
        </td>
      </template>
    </edit-category>
    <tbody v-else-if="!fetchedData && !editFlag">
      <tr>
        <th class="text-center" colspan="7">
          <font-awesome-icon 
          icon="spinner" 
          size="2x" 
          spin fixed-width>
          </font-awesome-icon> Loading...
        </th>
      </tr>
    </tbody>
  </table>
</template>

<script>
  import { APIService } from '../../services/APIService';
  import fieldAccessMixin from '../../mixins/fieldAccessMixin';

  const apiService = new APIService();

  export default {
    name: 'components-categories-list-component',
    mixins: [ fieldAccessMixin ],
    data: function() {
      return {
        editFlag: false,
        deleteFlag: false,
        editID: null,
        editUpdatedID: false, // Lock/unlock updated ID for editing
        errorID: null, // Current data ID that has validation error
        typeError: null,
        disabled: '',
        ariaDisabledReadOnly: false,
        highlightRowSuccessUpdated: false, // Row highlighting for updated data
        highlightRowError: false // Row highlighting for updating data with errors
      }
    },
    props: {
      fetchedData: {
        type: Array
      },
      addedId: {
        type: Number
      },
      editData: {
        type: Array
      },
      // Indicates that the specific record has been modified
      updatedId: {
        type: Number
      },
      highlightRowSuccessAdded: {
        type: Boolean
      }
    },

    components: { 
      
    },

    methods: {
      editItem: function(value) {
        console.log(`Edit item :: ${value}`);
        this.editFlag = true;
        this.editID = value;
        // Clear the updated ID flag in unlocking each edit field
        (this.updatedId) ? this.unlockUpdatedID : '';
      },

      updateCategory: function(modifiedData, id) {
        // Disable the button
        this.disabled = 'disabled';
        this.ariaDisabledReadOnly = true;
        console.log(modifiedData);

        // Consume an API endpoint with a payload
        let payload = {
          type: modifiedData.type,
          description: modifiedData.description
        }

        apiService.updateData(this.$route.name, payload, id).then((res) => {
          if (typeof res.data.message.error === 'undefined') {
            console.log(res.data.message);
            // Validation errors
            this.typeError = null;
            // Reload the list
            console.log(`Updated ID :: ${id}`);
            this.$emit('updatedData', { id: id, obj: this.$store.dispatch('categoryUpdate', id) });
            // Lock each edit field
            this.editUpdatedID = false;
            // Row highlighting for updated data
            this.highlightRowSuccessUpdated = true;
            setTimeout(() => this.highlightRowSuccessUpdated = false, 1000);
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          } else {
            console.log(res.data.message.error);
            // Validation errors
            this.typeError = (!modifiedData.type || res.data.message.error.type) ? res.data.message.error.type[0] : null;
            // Row highlighting for updating data with errors
            this.highlightRowError = true;
            setTimeout(() => this.highlightRowError = false, 1000);
            this.errorID = id;
            this.disabled = '';
            this.ariaDisabledReadOnly = false;
          }
        }).catch((err) => {
          this.disabled = '';
          this.ariaDisabledReadOnly = false;
        });

      },

      deleteCategory: function(id) {
        apiService.deleteData(this.$route.name, id).then((res) => {
          console.log(res.data.message);
          this.$emit('deleteId', id);
        }).catch((err) => {
          console.log(err);
        });
        
      },
    },

    mounted() {
      console.log(`editUpdatedID to ${this.editUpdatedID} for edit state.`);
    },

    computed: {
      unlockUpdatedID: function() {
        this.editUpdatedID = true;
        console.log(`In computed :: editUpdatedID to ${this.editUpdatedID} for edit state.`);
        return this.editUpdatedID;
      }
    }
  }
</script>
