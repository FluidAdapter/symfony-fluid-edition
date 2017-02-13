<template>
    <div class="tabs">
        <div class="entrance-tabNavigation">
            <div v-for="tab in tabs" @click="activate(tab)" :class="{ 'entrance-tabNavigation-item--active': (activeTab === tab) }" class="entrance-tabNavigation-item">
                {{ tab }}
            </div>
        </div>
        <div class="formLogin-tabs">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default{
        name: 'tabs',
        data: function() {
            return {
                activeTab: '',
                tabs: []
            }
        },
        methods: {
            activate: function(tab) {
                this.activeTab = tab;
                this.$emit('activateTab', tab);
            }
        }
    }
</script>