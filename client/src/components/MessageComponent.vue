<template>
    <div class="m-4">
        <div v-if="from" class="inline-block bg-white rounded-xl p-2 whitespace-normal">
            <span class="bg-clip-text">
                {{ message }}
            </span>
        </div>
        <div v-else class="flex justify-end">
            <div class="bg-second-color text-white p-3 rounded-lg">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'message',

    props: {
        message: String,
        from: Boolean
    }
}
</script>