import Header from '@/app/(app)/Header'
import NewsFeed from '@/components/NewsFeed'

export const metadata = {
    title: 'Haider Ali - Dashboard',
}

const Dashboard = () => {
    return (
        <>
            <Header title="Dashboard" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 pl-4 pr-8 bg-white border-b border-gray-200">
                            <NewsFeed />
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Dashboard