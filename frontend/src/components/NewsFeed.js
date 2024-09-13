'use client'
import axios from '@/lib/axios'
import Link from 'next/link'
import React, { useState, useEffect } from 'react'

const NewsFeed = () => {
    const [keyword, setKeyword] = useState('')
    const [categories, setCategories] = useState([])
    const [sources, setSources] = useState([])
    const [selectedCategory, setSelectedCategory] = useState('')
    const [selectedSource, setSelectedSource] = useState('')
    const [dateFrom, setDateFrom] = useState('')
    const [dateTo, setDateTo] = useState('')
    const [articles, setArticles] = useState([])

    // Fetch categories and sources on component mount
    useEffect(() => {
        axios.get('/api/categories').then(res => setCategories(res.data))
        axios.get('/api/sources').then(res => setSources(res.data))
        axios.get('/api/user/news-feed').then(res => setArticles(res.data.data))
    }, [])

    // Handle form submission
    const handleSearch = async e => {
        e.preventDefault()
        const response = await axios.get('/api/articles', {
            params: {
                keyword,
                category_id: selectedCategory,
                source_id: selectedSource,
                date_from: dateFrom,
                date_to: dateTo,
            },
        })
        setArticles(response.data.data) // Assuming the API returns paginated results
    }

    return (
        <div className="mx-auto p-4">
            {/* Search and Filter Form */}
            <form
                onSubmit={handleSearch}
                className="bg-white p-6 rounded-lg shadow-lg">
                <h2 className="text-xl font-bold mb-4">
                    Search and Filter Articles
                </h2>

                {/* Keyword Input */}
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">
                        Keyword
                    </label>
                    <input
                        type="text"
                        value={keyword}
                        onChange={e => setKeyword(e.target.value)}
                        className="w-full p-2 border border-gray-300 rounded-md"
                        placeholder="Enter keyword"
                    />
                </div>

                {/* Category Dropdown */}
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">
                        Category
                    </label>
                    <select
                        value={selectedCategory}
                        onChange={e => setSelectedCategory(e.target.value)}
                        className="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">All Categories</option>
                        {categories.map(category => (
                            <option key={category.id} value={category.id}>
                                {category.name}
                            </option>
                        ))}
                    </select>
                </div>

                {/* Source Dropdown */}
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">
                        Source
                    </label>
                    <select
                        value={selectedSource}
                        onChange={e => setSelectedSource(e.target.value)}
                        className="w-full p-2 border border-gray-300 rounded-md">
                        <option value="">All Sources</option>
                        {sources.map(source => (
                            <option key={source.id} value={source.id}>
                                {source.name}
                            </option>
                        ))}
                    </select>
                </div>

                {/* Date Range */}
                <div className="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label className="block text-gray-700 font-bold mb-2">
                            From
                        </label>
                        <input
                            type="date"
                            value={dateFrom}
                            onChange={e => setDateFrom(e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-md"
                        />
                    </div>
                    <div>
                        <label className="block text-gray-700 font-bold mb-2">
                            To
                        </label>
                        <input
                            type="date"
                            value={dateTo}
                            onChange={e => setDateTo(e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-md"
                        />
                    </div>
                </div>

                {/* Submit Button */}
                <button
                    type="submit"
                    className="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition">
                    Search
                </button>
            </form>

            {/* Articles Display */}
            <div className="mt-6">
                <h3 className="text-lg font-bold mb-4">Personal News Feed</h3>
                {articles.map((article, index) => (
                    <div
                        key={article.id}
                        className={`flex max-sm:block ${
                            index + 1 == articles.length
                                ? ''
                                : 'pb-10 mb-10 border-b'
                        }`}>
                        <div className="w-1/3 max-sm:w-full h-56 relative overflow-hidden rounded-lg">
                            <img
                                src={article.thumbnail_url}
                                className="object-cover w-full h-full"></img>
                        </div>

                        <div className="w-full pl-14 max-sm:pl-0">
                            <div className="flex justify-between items-center mb-6">
                                <h1 className="text-2xl font-bold">
                                    {article.title}
                                </h1>
                            </div>
                            <p>
                                <small className="text-gray-500 block">
                                    <strong>Source: </strong>{' '}
                                    {article.source?.name}
                                </small>
                                <small className="text-gray-500 block">
                                    <strong>Author: </strong>{' '}
                                    {article.author?.name}
                                </small>
                                <small className="text-gray-500 block">
                                    <strong>Published At: </strong>{' '}
                                    {new Date(
                                        article.published_at,
                                    ).toLocaleDateString()}
                                </small>
                            </p>
                            <Link href={article.url} target="_blank">
                                <span className="text-purple-600 font-bold">
                                    Read Full Article
                                </span>
                            </Link>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    )
}

export default NewsFeed
