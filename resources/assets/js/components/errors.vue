<template>
  <div>
    <transition
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
    >
      <div
        class="alert alert-danger"
        role="alert"
        v-if="$store.getters.validationErrors.length !== 0"
      >
        <ul
          class="list-group list-group-flush"
          v-if="typeof $store.getters.validationErrors === 'object'"
        >
          <li v-for="(items, index) in $store.getters.validationErrors">
            <p class="mb-0 animated fadeIn" v-for="(item, index) in items">
              <i class="icon icon-hand-stop-o text-blue"></i> {{ item }}
            </p>
          </li>
        </ul>

        <ul
          class="list-group list-group-flush"
          v-else-if="typeof $store.getters.validationErrors === 'string'"
        >
          <li>
            <p class="mb-0">
              <i class="icon icon-hand-stop-o text-blue"></i>
              {{ $store.getters.validationErrors }}
            </p>
          </li>
        </ul>
      </div>
    </transition>
  </div>
</template>

<script>
export default {};
</script>

<style></style>
