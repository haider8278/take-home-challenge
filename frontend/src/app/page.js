import LoginLinks from '@/app/LoginLinks'

export const metadata = {
    title: 'Laravel',
}

const Home = () => {
    return (
        <>
            <div className="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
                <LoginLinks />

                <div className="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex justify-center pt-8 sm:justify-start sm:pt-0">
                        <h1 className="text-2xl font-black">
                            <span className="text-purple-700">
                                Take Home Challenge
                            </span>{' '}
                            Haider
                        </h1>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Home
