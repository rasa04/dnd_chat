<template>
    <div class="m-4">
        <div v-if="fromAnotherPlayer" class="inline-block bg-white rounded-xl p-2 whitespace-normal">
            <span class="bg-clip-text">
                {{ message.body }} <br> <span class="text-gray-500 text-sm select-none">{{ message.time }}</span>
            </span>
        </div>
        <div v-else class="flex justify-end">
            <div class="bg-teal-800 text-white p-3 rounded-lg">
                {{ message.body }} <br> <span class="text-gray-500 text-sm select-none">{{ message.time }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'message',

    props: {
        message: Array,
        fromAnotherPlayer: Boolean,
    }
}
</script>