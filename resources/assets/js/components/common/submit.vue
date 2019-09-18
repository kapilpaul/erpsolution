<template>
  <div>
    <button
      v-if="this.$store.getters.submitted == false"
      class="btn btn-primary waves-effect"
      type="submit"
    >
      SUBMIT
    </button>
    <button
      v-else-if="this.$store.getters.submitted == true"
      class="btn btn-primary waves-effect"
      disabled
    >
      <i class="fa fa-spinner fa-spin"></i>
      Processing
    </button>
  </div>
</template>

<script>
export default {};
</script>

<style></style>
