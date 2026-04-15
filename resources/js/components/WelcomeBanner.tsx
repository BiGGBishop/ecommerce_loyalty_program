import { Gift } from 'lucide-react';

interface WelcomeBannerProps {
    userName: string;
    memberSince: string;
}

export default function WelcomeBanner({ userName, memberSince }: WelcomeBannerProps) {
    return (
        <div className="rounded-xl p-6 shadow-sm border border-sidebar-border/70 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
            <div className="flex items-center justify-between">
                <div>
                    <h2 className="text-2xl font-bold mb-2">Welcome back, {userName}! 👋</h2>
                    <p className="text-gray-600 dark:text-gray-400">Member since {memberSince}</p>
                </div>
                <Gift className="w-12 h-12 text-gray-400" />
            </div>
        </div>
    );
}