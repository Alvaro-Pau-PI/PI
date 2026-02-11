import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'

export function useRole() {
    const { user } = storeToRefs(useAuthStore())

    const can = (permission) => {
        // Si no hay user (o role), false
        const roleId = user.value?.role_id;
        // Mapeo básico ID -> Rol str (Asumiendo 1:Admin, 2:Vendedor, 3:Editor, 4:Usuario)
        // Ajustar según DB real.

        // TODO: Idealmente el backend debería devolver el rol.name o permissions list
        // Por ahora haremos un mapeo hardcodeado basado en la lógica típica
        let role = 'user';
        if (roleId === 1) role = 'admin';
        else if (roleId === 2) role = 'vendor';
        else if (roleId === 3) role = 'editor';

        const rules = {
            admin: ['create', 'edit', 'delete', 'moderate', 'manage_users'],
            vendor: ['create', 'edit', 'delete'], // Solo sus propios productos (lógica backend)
            editor: ['moderate'],
            user: ['read']
        }

        // Admin tiene permiso a todo si no está explícito en la lista
        if (role === 'admin') return true;

        return rules[role]?.includes(permission) ?? false
    }

    const isAdmin = () => {
        return user.value?.role_id === 1;
    }

    return { can, isAdmin }
}
