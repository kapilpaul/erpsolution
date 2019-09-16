<template>
    <div class="container-fluid pt-5">
        <div class="text-center p-5">
            <i class="icon-note-important s-64 text-primary"></i>
            <h4 class="my-3">No {{ name }} Found</h4>
            <p>You have not added any {{ name }} add first {{ name }} now</p>
            <a :href="url + 'create'" class="btn btn-primary shadow btn-lg"><i class="icon-plus-circle mr-2 "></i>Add
                New {{ name }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['name', 'url']
    }
</script>

<style>

</style>