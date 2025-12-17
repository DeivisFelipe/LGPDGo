<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const drawer = ref(true);
const rail = ref(false);

const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

const menuItems = [
    { title: 'Dashboard', icon: 'mdi-view-dashboard', route: 'dashboard' },
    { title: 'Usuários', icon: 'mdi-account-multiple', route: 'users.index', permission: 'view-users' },
    { title: 'Empresas', icon: 'mdi-domain', route: 'companies.index', permission: 'view-companies' },
    { title: 'Permissões', icon: 'mdi-shield-key', route: 'permissions.index', permission: 'view-permissions' },
    { title: 'Grupos', icon: 'mdi-account-group', route: 'permission-groups.index', permission: 'view-permission-groups' },
];

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <v-app theme="dark">
        <Head title="LGPDGo" />
        
        <!-- App Bar -->
        <v-app-bar color="primary" prominent>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            
            <v-toolbar-title class="text-h6 font-weight-bold">
                <v-icon class="mr-2">mdi-shield-lock</v-icon>
                LGPDGo
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <!-- Company Info -->
            <v-chip v-if="company" class="mr-4" prepend-icon="mdi-domain">
                {{ company.name }}
            </v-chip>

            <!-- User Menu -->
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn icon v-bind="props">
                        <v-avatar color="secondary">
                            <span class="text-h6">{{ user?.name?.charAt(0) }}</span>
                        </v-avatar>
                    </v-btn>
                </template>
                
                <v-list>
                    <v-list-item>
                        <v-list-item-title>{{ user?.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ user?.email }}</v-list-item-subtitle>
                    </v-list-item>
                    
                    <v-divider></v-divider>
                    
                    <v-list-item :href="route('profile.edit')" prepend-icon="mdi-account">
                        <v-list-item-title>Perfil</v-list-item-title>
                    </v-list-item>
                    
                    <v-list-item @click="logout" prepend-icon="mdi-logout">
                        <v-list-item-title>Sair</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Navigation Drawer -->
        <v-navigation-drawer v-model="drawer" :rail="rail" permanent>
            <v-list-item
                prepend-icon="mdi-shield-lock"
                :title="rail ? '' : 'LGPDGo'"
                nav
            >
                <template v-slot:append>
                    <v-btn
                        icon="mdi-chevron-left"
                        variant="text"
                        @click="rail = !rail"
                    ></v-btn>
                </template>
            </v-list-item>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item
                    v-for="item in menuItems"
                    :key="item.title"
                    :prepend-icon="item.icon"
                    :title="item.title"
                    :href="item.route ? route(item.route) : '#'"
                    :active="item.route && route().current(item.route)"
                ></v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-main>
            <v-container fluid>
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>
