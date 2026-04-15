import { Head } from '@inertiajs/react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { dashboard } from '@/routes';
import { Trophy, Lock, Award, TrendingUp, Star, Gift } from 'lucide-react';

interface LoyaltyData {
    unlocked_achievements: string[];
    next_available_achievements: string[];
    current_badge: string;
    next_badge: string;
    remaining_to_unlock_next_badge: number;
}

interface DashboardProps {
    loyaltyData: LoyaltyData;
}

export default function Dashboard({ loyaltyData }: DashboardProps) {
    const progressPercent = loyaltyData?.remaining_to_unlock_next_badge > 0 
        ? ((5 - loyaltyData.remaining_to_unlock_next_badge) / 5) * 100 
        : 100;

    return (
        <>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                {/* Loyalty Rewards Section - No background */}
                <div className="rounded-xl p-6 shadow-sm border border-sidebar-border/70">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold mb-2">🏆 My Loyalty Rewards</h2>
                            <p className="text-gray-600 dark:text-gray-400">Keep shopping to unlock more benefits and cashback!</p>
                        </div>
                        <Gift className="w-12 h-12 text-gray-400" />
                    </div>
                </div>

                {/* Current Badge & Progress */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {/* Current Badge Card - No background */}
                    <div className="rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
                        <div className="flex items-center gap-3 mb-4">
                            <Award className="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            <h3 className="text-lg font-semibold">Current Badge</h3>
                        </div>
                        <div className="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {loyaltyData?.current_badge || 'Newbie'}
                        </div>
                        <p className="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                            Next badge: {loyaltyData?.next_badge}
                        </p>
                    </div>

                    {/* Progress Card - No background */}
                    <div className="rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
                        <div className="flex items-center gap-3 mb-4">
                            <TrendingUp className="w-6 h-6 text-green-600 dark:text-green-400" />
                            <h3 className="text-lg font-semibold">Progress to Next Badge</h3>
                        </div>
                        <div className="mb-2 flex justify-between text-sm">
                            <span className="text-gray-600 dark:text-gray-400">Progress</span>
                            <span className="text-gray-600 dark:text-gray-400">{Math.round(progressPercent)}%</span>
                        </div>
                        <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div 
                                className="bg-gradient-to-r from-green-500 to-green-600 rounded-full h-3 transition-all duration-500"
                                style={{ width: `${progressPercent}%` }}
                            />
                        </div>
                        <p className="text-gray-500 dark:text-gray-400 mt-3 text-sm">
                            {loyaltyData?.remaining_to_unlock_next_badge} more achievements to unlock {loyaltyData?.next_badge}
                        </p>
                    </div>
                </div>

                {/* Achievements Section - No background */}
                <div className="rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
                    <div className="flex items-center gap-3 mb-6">
                        <Trophy className="w-6 h-6 text-yellow-600 dark:text-yellow-500" />
                        <h3 className="text-lg font-semibold">My Achievements</h3>
                    </div>

                    {/* Unlocked Achievements */}
                    <div className="mb-8">
                        <h4 className="text-md font-medium text-green-600 dark:text-green-400 mb-3 flex items-center gap-2">
                            <Star className="w-4 h-4" />
                            Unlocked ({loyaltyData?.unlocked_achievements?.length || 0})
                        </h4>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {loyaltyData?.unlocked_achievements?.map((achievement: string, index: number) => (
                                <div key={index} className="flex items-center gap-3 p-3 rounded-lg border border-green-200 dark:border-green-800">
                                    <Trophy className="w-5 h-5 text-green-600 dark:text-green-400" />
                                    <span className="font-medium">{achievement}</span>
                                </div>
                            ))}
                            {(!loyaltyData?.unlocked_achievements || loyaltyData.unlocked_achievements.length === 0) && (
                                <p className="text-gray-500 dark:text-gray-400 col-span-2 text-center py-4">
                                    No achievements yet. Start shopping to unlock your first achievement!
                                </p>
                            )}
                        </div>
                    </div>

                    {/* Next Achievements */}
                    <div>
                        <h4 className="text-md font-medium text-gray-600 dark:text-gray-400 mb-3 flex items-center gap-2">
                            <Lock className="w-4 h-4" />
                            Next Achievements
                        </h4>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {loyaltyData?.next_available_achievements?.map((achievement: string, index: number) => (
                                <div key={index} className="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <Lock className="w-5 h-5 text-gray-500 dark:text-gray-500" />
                                    <span className="text-gray-500 dark:text-gray-400">{achievement}</span>
                                </div>
                            ))}
                            {(!loyaltyData?.next_available_achievements || loyaltyData.next_available_achievements.length === 0) && (
                                <p className="text-gray-500 dark:text-gray-400 col-span-2 text-center py-4">
                                    You've unlocked all achievements! 🎉
                                </p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};