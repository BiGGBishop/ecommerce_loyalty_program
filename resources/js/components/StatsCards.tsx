import { ShoppingCart, Wallet } from 'lucide-react';

interface StatsCardsProps {
    totalPurchases: number;
    totalSpent: number;
}

export default function StatsCards({ totalPurchases, totalSpent }: StatsCardsProps) {
    const formatNaira = (amount: number) => {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    };

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
                <div className="flex items-center gap-3 mb-4">
                    <ShoppingCart className="w-6 h-6 text-blue-600" />
                    <h3 className="text-lg font-semibold">Total Purchases</h3>
                </div>
                <div className="text-3xl font-bold text-blue-600">
                    {totalPurchases || 0}
                </div>
                <p className="text-gray-500 mt-2 text-sm">Lifetime orders</p>
            </div>
            
            <div className="rounded-xl border border-sidebar-border/70 p-6 shadow-sm">
                <div className="flex items-center gap-3 mb-4">
                    <Wallet className="w-6 h-6 text-green-600" />
                    <h3 className="text-lg font-semibold">Total Spent</h3>
                </div>
                <div className="text-3xl font-bold text-green-600">
                    {formatNaira(totalSpent || 0)}
                </div>
                <p className="text-gray-500 mt-2 text-sm">Across all purchases</p>
            </div>
        </div>
    );
}