import { useState } from 'react';
import { Package, X, Plus, Minus } from 'lucide-react';

interface Product {
    id: string;
    name: string;
    description: string;
    price: number;
    stock: number;
}

interface ProductModalProps {
    isOpen: boolean;
    onClose: () => void;
    products: Product[];
    selectedProduct?: Product | null;
    onPurchase: (productId: string, quantity: number) => Promise<void>;
    onSelectProduct?: (product: Product) => void;
}

export default function ProductModal({ 
    isOpen, 
    onClose, 
    products, 
    selectedProduct = null,
    onPurchase, 
    onSelectProduct 
}: ProductModalProps) {
    const [quantity, setQuantity] = useState(1);
    const [purchasing, setPurchasing] = useState(false);

    const formatNaira = (amount: number) => {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    };

    const handlePurchase = async () => {
        if (!selectedProduct) return;
        
        setPurchasing(true);
        try {
            await onPurchase(selectedProduct.id, quantity);
            setQuantity(1);
            onClose();
        } catch (error) {
            console.error('Purchase failed:', error);
        } finally {
            setPurchasing(false);
        }
    };

    const handleClose = () => {
        setQuantity(1);
        onClose();
    };

    const handleSelectProduct = (product: Product) => {
        if (onSelectProduct) {
            onSelectProduct(product);
        }
        setQuantity(1);
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div className="bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto">
                <div className="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 className="text-2xl font-bold">
                        {selectedProduct ? 'Complete Purchase' : 'All Products'}
                    </h2>
                    <button
                        onClick={handleClose}
                        className="text-gray-500 hover:text-gray-700"
                    >
                        <X className="w-6 h-6" />
                    </button>
                </div>
                
                <div className="p-6">
                    {!selectedProduct ? (
                        <>
                            {products.length === 0 ? (
                                <div className="text-center py-8 text-gray-500">
                                    <Package className="w-16 h-16 mx-auto mb-4 opacity-50" />
                                    <p>No products available. Please run the product seeder.</p>
                                </div>
                            ) : (
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {products.map((product) => (
                                        <div
                                            key={product.id}
                                            className="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all cursor-pointer"
                                            onClick={() => handleSelectProduct(product)}
                                        >
                                            <Package className="w-8 h-8 text-blue-600 mb-3" />
                                            <h3 className="font-semibold text-lg mb-2">{product.name}</h3>
                                            <p className="text-gray-500 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                                {product.description || 'No description available'}
                                            </p>
                                            <div className="flex justify-between items-center">
                                                <span className="text-2xl font-bold text-green-600">
                                                    {formatNaira(product.price)}
                                                </span>
                                                <span className="text-sm text-gray-500">
                                                    Stock: {product.stock}
                                                </span>
                                            </div>
                                            <button
                                                className="w-full mt-3 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    handleSelectProduct(product);
                                                }}
                                            >
                                                Buy Now
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </>
                    ) : (
                        <div>
                            <button
                                onClick={() => {
                                    if (onSelectProduct) {
                                        onSelectProduct(null as any);
                                    }
                                    setQuantity(1);
                                }}
                                className="mb-4 text-blue-600 hover:text-blue-700 flex items-center gap-2"
                            >
                                ← Back to products
                            </button>
                            
                            <div className="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                <h3 className="text-2xl font-bold mb-2">{selectedProduct.name}</h3>
                                <p className="text-gray-500 dark:text-gray-400 mb-4">{selectedProduct.description || 'No description available'}</p>
                                
                                <div className="space-y-4">
                                    <div className="flex justify-between items-center">
                                        <span className="text-gray-600">Price:</span>
                                        <span className="text-2xl font-bold text-green-600">
                                            {formatNaira(selectedProduct.price)}
                                        </span>
                                    </div>
                                    
                                    <div className="flex justify-between items-center">
                                        <span className="text-gray-600">Available Stock:</span>
                                        <span>{selectedProduct.stock} units</span>
                                    </div>
                                    
                                    <div className="flex justify-between items-center">
                                        <span className="text-gray-600">Quantity:</span>
                                        <div className="flex items-center gap-3">
                                            <button
                                                onClick={() => setQuantity(Math.max(1, quantity - 1))}
                                                className="p-1 rounded border border-gray-300 hover:bg-gray-100"
                                                disabled={quantity <= 1}
                                            >
                                                <Minus className="w-4 h-4" />
                                            </button>
                                            <span className="text-xl font-semibold min-w-[40px] text-center">{quantity}</span>
                                            <button
                                                onClick={() => setQuantity(Math.min(selectedProduct.stock, quantity + 1))}
                                                className="p-1 rounded border border-gray-300 hover:bg-gray-100"
                                                disabled={quantity >= selectedProduct.stock}
                                            >
                                                <Plus className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div className="border-t pt-4 mt-4">
                                        <div className="flex justify-between items-center mb-4">
                                            <span className="text-lg font-semibold">Total:</span>
                                            <span className="text-2xl font-bold text-green-600">
                                                {formatNaira(selectedProduct.price * quantity)}
                                            </span>
                                        </div>
                                        
                                        <button
                                            onClick={handlePurchase}
                                            disabled={purchasing}
                                            className="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-all"
                                        >
                                            {purchasing ? 'Processing...' : 'Confirm Purchase'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}