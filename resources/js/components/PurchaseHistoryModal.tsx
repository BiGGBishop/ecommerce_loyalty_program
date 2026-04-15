import { ShoppingCart, X } from 'lucide-react';

interface Purchase {
    id: string;
    product_name: string;
    amount: number;
    transaction_id: string;
    date: string;
}

interface PurchaseHistoryModalProps {
    isOpen: boolean;
    onClose: () => void;
    purchases: Purchase[];
    loading: boolean;
}

export default function PurchaseHistoryModal({ isOpen, onClose, purchases, loading }: PurchaseHistoryModalProps) {
    const formatNaira = (amount: number) => {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div className="bg-white dark:bg-gray-800 rounded-xl max-w-3xl w-full max-h-[80vh] overflow-y-auto">
                <div className="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 className="text-2xl font-bold">Purchase History</h2>
                    <button
                        onClick={onClose}
                        className="text-gray-500 hover:text-gray-700"
                    >
                        <X className="w-6 h-6" />
                    </button>
                </div>
                
                <div className="p-6">
                    {loading ? (
                        <div className="text-center py-8">Loading...</div>
                    ) : purchases.length === 0 ? (
                        <div className="text-center py-8 text-gray-500">
                            <ShoppingCart className="w-16 h-16 mx-auto mb-4 opacity-50" />
                            <p>No purchases yet. Start shopping to earn rewards!</p>
                        </div>
                    ) : (
                        <div className="space-y-3">
                            {purchases.map((purchase) => (
                                <div
                                    key={purchase.id}
                                    className="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all"
                                >
                                    <div>
                                        <p className="font-semibold text-lg">{purchase.product_name}</p>
                                        <p className="text-sm text-gray-500 dark:text-gray-400">{purchase.date}</p>
                                        <p className="text-xs text-gray-400 mt-1">Transaction: {purchase.transaction_id}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-xl font-bold text-green-600">{formatNaira(purchase.amount)}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}