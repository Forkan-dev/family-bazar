import {
    ArrowLeft,
    BarChart3,
    Bell,
    Camera,
    ChevronDown,
    DollarSign,
    FolderTree,
    Home,
    Info,
    LogOut,
    Mail,
    MapPin,
    Package,
    Pencil,
    Plus,
    Save,
    Search,
    Settings,
    Shield,
    Trash2,
    User,
    Users,
    Weight,
    X,
    type LucideIcon,
} from 'lucide-vue-next';

// Map MDI icon names to Lucide icons
const iconMap: Record<string, LucideIcon> = {
    'mdi-plus': Plus,
    'mdi-pencil': Pencil,
    'mdi-arrow-left': ArrowLeft,
    'mdi-information': Info,
    'mdi-currency-usd': DollarSign,
    'mdi-currency-bdt': DollarSign,
    'mdi-weight': Weight,
    'mdi-camera': Camera,
    'mdi-content-save': Save,
    'mdi-close': X,
    'mdi-magnify': Search,
    'mdi-delete': Trash2,
    'mdi-folder-tree': FolderTree,
    'mdi-package': Package,
    'mdi-package-variant': Package,
    'mdi-bell-outline': Bell,
    'mdi-email-outline': Mail,
    'mdi-chevron-down': ChevronDown,
    'mdi-view-dashboard': Home,
    'mdi-cog': Settings,
    'mdi-logout': LogOut,
    'mdi-account': User,
    'mdi-account-group-outline': Users,
    'mdi-shield-crown-outline': Shield,
    'mdi-shield-account': Shield,
    'mdi-shield-key': Shield,
    'mdi-account-key': Shield,
    'mdi-chart-line': BarChart3,
    'mdi-map-marker': MapPin,
    'mdi-watermark': Package, // fallback
    'mdi-shape': FolderTree, // fallback
    'mdi-alarm': Bell, // fallback
    'mdi-folder-multiple-outline': FolderTree,
};

export function getMdiIcon(iconName: string): LucideIcon | undefined {
    return iconMap[iconName];
}

export function convertMdiToLucide(iconName: string): LucideIcon {
    return iconMap[iconName] || Info; // fallback to Info icon
}
