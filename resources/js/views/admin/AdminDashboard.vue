<template>
    <div class="wrap-dashboard admin-panel max">
        <div class="dashboard-col-1">
            <div class="wrap-dashboard-button">
                <div class="dashboard-button news"><a href="/admin/posts">Новости</a></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AdminDashboard.vue"
}
</script>

<style scoped>

.wrap-admin-link span {
    color:#fff;
}

.dashboard-button {
    background-color: #ffffff;
    margin-top: 15px;
    margin-bottom: 30px;
    display: flex;
    border-bottom: 1px solid #5d5d5d;
}
.dashboard-button a {
    padding: 10px;
}
.dashboard-button a:hover{
    background-color: #8b8b8b;
    color: #fff;
}
</style>
