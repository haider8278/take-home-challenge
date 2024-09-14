'use client'
import axios from '@/lib/axios'
import React, { useState, useEffect } from 'react'
import Header from '../Header'

const Profile = () => {
    const [sources, setSources] = useState([])
    const [categories, setCategories] = useState([])
    const [authors, setAuthors] = useState([])
    const [preferences, setPreferences] = useState({
        preferred_sources: [],
        preferred_categories: [],
        preferred_authors: [],
    })

    // Fetch the options (sources, categories, authors) and user preferences
    useEffect(() => {
        axios.get('/api/sources').then(res => setSources(res.data))
        axios.get('/api/categories').then(res => setCategories(res.data))
        axios.get('/api/authors').then(res => setAuthors(res.data))
        axios.get('/api/user/preferences').then(res => setPreferences(res.data))
    }, [])

    // Handle saving preferences
    const savePreferences = e => {
        e.preventDefault()
        axios
            .post('/api/user/preferences', preferences)
            .then(() => alert('Preferences saved successfully!'))
            .catch(() => alert('Error saving preferences'))
    }

    return (
        <>
            <Header title="Dashboard" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <form onSubmit={savePreferences}>
                                <h2 className="text-lg font-bold py-3">
                                    Select Preferred Sources
                                </h2>
                                <ul className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    {sources.map(source => (
                                        <li
                                            className="flex items-center"
                                            key={source.id}>
                                            <input
                                                className="mr-2"
                                                type="checkbox"
                                                value={source.id}
                                                checked={
                                                    preferences.preferred_sources?.includes(
                                                        source.id,
                                                    ) || false
                                                } // Safe access with default false
                                                onChange={e => {
                                                    const updatedSources = e
                                                        .target.checked
                                                        ? [
                                                              ...(preferences.preferred_sources ||
                                                                  []),
                                                              source.id,
                                                          ] // Ensure preferred_sources is an array
                                                        : preferences.preferred_sources?.filter(
                                                              id =>
                                                                  id !==
                                                                  source.id,
                                                          ) || [] // Safe access with fallback
                                                    setPreferences({
                                                        ...preferences,
                                                        preferred_sources:
                                                            updatedSources,
                                                    })
                                                }}
                                            />
                                            <label>{source.name}</label>
                                        </li>
                                    ))}
                                </ul>

                                <h2 className="text-lg font-bold py-3">
                                    Select Preferred Categories
                                </h2>
                                <ul className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    {categories.map(category => (
                                        <li
                                            key={category.id}
                                            className="flex items-center">
                                            <input
                                                className="mr-2"
                                                type="checkbox"
                                                value={category.id}
                                                checked={
                                                    preferences.preferred_categories?.includes(
                                                        category.id,
                                                    ) || false
                                                }
                                                onChange={e => {
                                                    const updatedCategories = e
                                                        .target.checked
                                                        ? [
                                                              ...(preferences.preferred_categories || []),
                                                              category.id,
                                                          ]
                                                        : preferences.preferred_categories.filter(
                                                              id =>
                                                                  id !==
                                                                  category.id,
                                                          ) || []
                                                    setPreferences({
                                                        ...preferences,
                                                        preferred_categories:
                                                            updatedCategories,
                                                    })
                                                }}
                                            />
                                            <label>{category.name}</label>
                                        </li>
                                    ))}
                                </ul>

                                <h2 className="text-lg font-bold py-3">
                                    Select Preferred Authors
                                </h2>
                                <ul className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    {authors.map(author => (
                                        <li
                                            key={author.id}
                                            className="flex items-center">
                                            <input
                                                className="mr-2"
                                                type="checkbox"
                                                value={author.id}
                                                checked={
                                                    preferences.preferred_authors?.includes(
                                                        author.id,
                                                    ) || false
                                                }
                                                onChange={e => {
                                                    const updatedAuthors = e
                                                        .target.checked
                                                        ? [
                                                              ...(preferences.preferred_authors || []),
                                                              author.id,
                                                          ]
                                                        : preferences.preferred_authors.filter(
                                                              id =>
                                                                  id !==
                                                                  author.id,
                                                          ) || []
                                                    setPreferences({
                                                        ...preferences,
                                                        preferred_authors:
                                                            updatedAuthors,
                                                    })
                                                }}
                                            />
                                            <label>{author.name}</label>
                                        </li>
                                    ))}
                                </ul>

                                <button className="button bg-black text-white p-3 hover:bg-purple-900" type="submit">Save Preferences</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Profile
