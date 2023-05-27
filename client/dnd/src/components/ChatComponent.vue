<template>
    <div class="bg-second-color col-span-3 flex flex-col h-full pt-2">
        <div class="flex-grow bg-cover rounded-xl"
            style="background-image: url('./src/assets/img/background.jpg')"
        >
            <MessageComponent :from="true" message="как то так получается" />
            <MessageComponent :from="false" message="как тебе?" />
            <MessageComponent :from="true" message="я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс я нарцисс " />
            <MessageComponent :from="false" message="я знаю" />
        </div>
        <div class="flex justify-center items-center p-4 bg-second-color">
            <input type="text" placeholder="Write..." class="mr-2 px-4 py-2 border rounded-2xl focus:outline-none w-full" />
            <button class="px-4 py-2 bg-second-color text-white rounded-2xl focus:outline-none">
                <font-awesome-icon :icon="['fas', 'paper-plane']" />
            </button>
        </div>
    </div>
</template>

<script>
import MessageComponent from './MessageComponent.vue';
export default{
    name: "chat",
    props: {},
    components: { MessageComponent }
}
</script>
