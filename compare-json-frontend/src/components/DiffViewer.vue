<template>
  <div class="content">
    <div class="box">
      <h3 class="title is-4">Title & Description</h3>

      <div v-if="diff.title" class="block">
        <p class="has-text-weight-bold">Title changed:</p>

        <div class="mb-1">
          From: <span class="has-text-danger">{{ diff.title.from }}</span>
        </div>
        <div>
          To: <span class="has-text-success">{{ diff.title.to }}</span>
        </div>
      </div>

      <div v-if="diff.description" class="block">
        <p class="has-text-weight-bold">Description changed:</p>

        <div class="mb-1">
          From: <span class="has-text-danger">{{ diff.description.from }}</span>
        </div>
        <div>
          To: <span class="has-text-success">{{ diff.description.to }}</span>
        </div>
      </div>
    </div>

    <div class="box">
      <h3 class="title is-4">Images</h3>

      <div v-if="diff.images.added.length" class="block">
        <p class="has-text-weight-bold">Added Images:</p>

        <div v-for="img in diff.images.added" :key="img.id">
          <span class="tag is-success mb-1">Image {{ img.id }}</span> - {{ img.url }}
        </div>
      </div>

      <div v-if="diff.images.removed.length" class="block">
        <p class="has-text-weight-bold">Removed Images:</p>

        <div v-for="img in diff.images.removed" :key="img.id">
          <span class="tag is-danger mb-1">Image {{ img.id }}</span> - {{ img.url }}
        </div>
      </div>

      <div v-if="diff.images.changed.length" class="block">
        <p class="has-text-weight-bold">Changed Images:</p>

        <div v-for="img in diff.images.changed" :key="img.id" class="mb-5">
          <span class="tag is-warning mb-1">Image {{ img.id }}</span>
          <div v-for="(change, field) in img.changes" :key="field">
            <div>{{ field }} is changed</div>
            <div>
              From: <span class="has-text-danger"> {{ change.from }}</span>
            </div>
            <div>
              To: <span class="has-text-success">{{ change.to }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="box">
      <h3 class="title is-4">Variants</h3>

      <div v-if="diff.variants.added.length" class="block">
        <p class="has-text-weight-bold">Added Variants:</p>

        <div v-for="v in diff.variants.added" :key="v.id">
          <span class="tag is-success mb-1">{{ v.id }}</span> - SKU: {{ v.sku }}
        </div>
      </div>

      <div v-if="diff.variants.removed.length" class="block">
        <p class="has-text-weight-bold">Removed Variants:</p>

        <div v-for="v in diff.variants.removed" :key="v.id">
          <span class="tag is-danger mb-1">{{ v.id }}</span> - SKU: {{ v.sku }}
        </div>
      </div>

      <div v-if="diff.variants.changed.length" class="block">
        <p class="has-text-weight-bold">Changed Variants:</p>

        <div v-for="v in diff.variants.changed" :key="v.id" class="mb-5">
          <span class="tag is-warning mb-1">Variant {{ v.id }}</span>
          <div v-for="(change, field) in v.changes" :key="field" class="mb-3">
            <div>{{ field }} is changed</div>
            <div>
              From: <span class="has-text-danger"> {{ change.from }}</span>
            </div>
            <div>
              To: <span class="has-text-success">{{ change.to }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  diff: Object,
})
</script>
