import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'

export function useRole() {
    const { user } = storeToRefs(useAuthStore())

    const can = (permission) => {
        // Obtenemos el rol directamente del usuario (string: 'admin', 'user')
        const userRole = user.value?.role || 'user';

        const rules = {
            admin: ['create', 'edit', 'delete', 'moderate', 'manage_users'],
            vendor: ['create', 'edit', 'delete'],
            editor: ['moderate'],
            user: ['read']
        }

        if (userRole === 'admin') return true;

        return rules[userRole]?.includes(permission) ?? false
    }

    const isAdmin = () => {
        return user.value?.role === 'admin';
    }

    return { can, isAdmin }
}
